<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Woocommerce_Ir_Gateway_Melli_New' ) ) :

	Persian_Woocommerce_Gateways::register( 'Melli_New' );

	class Woocommerce_Ir_Gateway_Melli_New extends Persian_Woocommerce_Gateways {

		public function __construct() {

			$this->method_title = 'سداد ملی جدید';

			parent::init( $this );
		}

		public function fields() {
			return array(
				'merchant'          => array(
					'title'       => 'شماره پذیرنده (MerchantID)',
					'type'        => 'text',
					'description' => 'شماره پذیرنده (مرچنت) درگاه سداد ملی',
					'default'     => '',
					'desc_tip'    => true
				),
				'terminal'          => array(
					'title'       => 'شماره ترمینال (TerminalID)',
					'type'        => 'text',
					'description' => 'شماره ترمینال درگاه سداد ملی',
					'default'     => '',
					'desc_tip'    => true
				),
				'terminalkey'       => array(
					'title'       => 'کلید ترمینال (TerminalKey)',
					'type'        => 'password',
					'description' => 'کلید ترمینال درگاه سداد ملی',
					'default'     => '',
					'desc_tip'    => true
				),
				'cancelled_massage' => array(),
				'shortcodes'        => array(
					'transaction_id' => 'کد رهگیری (شماره مرجع تراکنش)',
					'TraceNo'        => 'شماره پیگیری داخلی تراکنش',
					'OrderId'        => 'شماره سفارش',
				)
			);
		}

		public function request( $order ) {

			$MerchantID  = $this->option( 'merchant' );
			$TerminalID  = $this->option( 'terminal' );
			$TerminalKey = $this->option( 'terminalkey' );
			$Amount      = $this->get_total( 'IRR' );
			$ResNum      = time();
			$ReturnPath  = $this->get_verify_url();

			require_once 'functions.php';

			$Parameters = array(
				'MerchantID'    => $MerchantID,
				'TerminalId'    => $TerminalID,
				'Amount'        => $Amount,
				'OrderId'       => $ResNum,
				'ReturnUrl'     => $ReturnPath,
				'LocalDateTime' => date( 'Ymdhis' ),
				'SignData'      => encrypt_pkcs7( $TerminalID . ';' . $ResNum . ';' . $Amount, $TerminalKey ),
			);

			try {
				$Result = sadad_curl( 'https://sadad.shaparak.ir/VPG/api/v0/Request/PaymentRequest', $Parameters );
				if ( ! empty( $Result ) && isset( $Result->Token, $Result->ResCode ) && $Result->ResCode == 0 ) {
					return $this->redirect( 'https://sadad.shaparak.ir/VPG/Purchase?Token=' . $Result->Token );
				} else {
					$error = isset( $Result->ResCode ) ? ' ::: ' . $this->errors( $Result->ResCode ) : '';

					return ( ! empty( $Result->Description ) ? $Result->Description : '' ) . $error;
				}
			} catch ( Exception $ex ) {
				return $ex->getMessage();
			}
		}

		public function verify( $order ) {

			require_once 'functions.php';

			//$Amount      = $this->get_total( 'IRR' );
			//$MerchantID  = $this->option( 'merchant' );
			//$TerminalID  = $this->option( 'terminal' );
			$TerminalKey = $this->option( 'terminalkey' );

			$Token         = $this->post( 'token' );
			$OrderId       = $this->post( 'OrderId' );
			$ResCode       = $this->post( 'ResCode', '0' );
			$RetrivalRefNo = '';
			$TraceNo       = '';

			$this->check_verification( $Token );

			$status = 'failed';
			$error  = array();
			if ( ! empty( $Token ) && $ResCode == 0 ) {

				try {

					$Result = sadad_curl( 'https://sadad.shaparak.ir/VPG/api/v0/Advice/Verify', array(
						'Token'    => $Token,
						'SignData' => encrypt_pkcs7( $Token, $TerminalKey ),
					) );

					if ( ! empty( $Result ) && isset( $Result->ResCode ) && $Result->ResCode == 0 ) {
						$status        = 'completed';
						$RetrivalRefNo = ! empty( $Result->RetrivalRefNo ) ? $Result->RetrivalRefNo : '';
						$TraceNo       = ! empty( $Result->SystemTraceNo ) ? $Result->SystemTraceNo : '';
						$OrderId       = ! empty( $Result->OrderId ) ? $Result->OrderId : '';
						//$VerifyAmount  = ! empty( $Result->Amount ) ? $Result->Amount : '';
					} else {
						$fault = ! empty( $Result->ResCode ) ? $Result->ResCode : '';
					}
				} catch ( Exception $ex ) {
					$error[] = $ex->getMessage();
				}
			} else {
				$fault = $ResCode;
			}

			$error[] = ! empty( $fault ) ? $this->errors( $fault ) : '';
			$error[] = ! empty( $Result ) && ! empty( $Result->Description ) ? $Result->Description : '';
			$error   = ! empty( $error ) ? implode( ' ::: ', $error ) : 'تراکنش انجام نشد.';

			$transaction_id = ! empty( $RetrivalRefNo ) ? $RetrivalRefNo : '';

			$this->set_shortcodes( array(
				'transaction_id' => $transaction_id,
				'TraceNo'        => $TraceNo,
				'OrderId'        => $OrderId
			) );

			return compact( 'status', 'transaction_id', 'error' );
		}

		private function errors( $error ) {

			switch ( $error ) {

				case - 1:
					$message = 'پارامترهای ارسالی صحیح نیست و يا تراکنش در سیستم وجود ندارد.';
					break;
				case 3:
					$message = 'پذيرنده کارت فعال نیست لطفا با بخش امورپذيرندگان, تماس حاصل فرمائید.';
					break;
				case 23:
					$message = 'پذيرنده کارت نامعتبر است لطفا با بخش امورذيرندگان, تماس حاصل فرمائید.';
					break;
				case 58:
					$message = 'انجام تراکنش مربوطه توسط پايانه ی انجام دهنده مجاز نمی باشد.';
					break;
				case 61:
					$message = 'مبلغ تراکنش از حد مجاز بالاتر است.';
					break;
				case 101:
					$message = 'مهلت ارسال تراکنش به پايان رسیده است.';
					break;
				case 1000:
					$message = 'ترتیب پارامترهای ارسالی اشتباه می باشد, لطفا مسئول فنی پذيرنده با بانکماس حاصل فرمايند.';
					break;
				case 1001:
					$message = 'لطفا مسئول فنی پذيرنده با بانک تماس حاصل فرمايند,پارامترهای پرداختاشتباه می باشد.';
					break;
				case 1002:
					$message = 'خطا در سیستم- تراکنش ناموفق';
					break;
				case 1003:
					$message = 'آی پی پذیرنده اشتباه است. لطفا مسئول فنی پذیرنده با بانک تماس حاصل فرمایند.';
					break;
				case 1004:
					$message = 'لطفا مسئول فنی پذيرنده با بانک تماس حاصل فرمايند,شماره پذيرندهاشتباه است.';
					break;
				case 1005:
					$message = 'خطای دسترسی:لطفا بعدا تلاش فرمايید.';
					break;
				case 1006:
					$message = 'خطا در سیستم';
					break;
				case 1011:
					$message = 'درخواست تکراری- شماره سفارش تکراری می باشد.';
					break;
				case 1012:
					$message = 'اطلاعات پذيرنده صحیح نیست,يکی از موارد تاريخ,زمان يا کلید تراکنش اشتباه است.لطفا مسئول فنی پذيرنده با بانک تماس حاصل فرمايند.';
					break;
				case 1015:
					$message = 'پاسخ خطای نامشخص از سمت مرکز';
					break;
				case 1017:
					$message = 'مبلغ درخواستی شما جهت پرداخت از حد مجاز تعريف شده برای اين پذيرنده بیشتر است';
					break;
				case 1018:
					$message = 'اشکال در تاريخ و زمان سیستم. لطفا تاريخ و زمان سرور خود را با بانک هماهنگ نمايید';
					break;
				case 1019:
					$message = 'امکان پرداخت از طريق سیستم شتاب برای اين پذيرنده امکان پذير نیست';
					break;
				case 1020:
					$message = 'پذيرنده غیرفعال شده است.لطفا جهت فعال سازی با بانک تماس بگیريد';
					break;
				case 1023:
					$message = 'آدرس بازگشت پذيرنده نامعتبر است';
					break;
				case 1024:
					$message = 'مهر زمانی پذيرنده نامعتبر است';
					break;
				case 1025:
					$message = 'امضا تراکنش نامعتبر است';
					break;
				case 1026:
					$message = 'شماره سفارش تراکنش نامعتبر است';
					break;
				case 1027:
					$message = 'شماره پذيرنده نامعتبر است';
					break;
				case 1028:
					$message = 'شماره ترمینال پذيرنده نامعتبر است';
					break;
				case 1029:
					$message = 'آدرس IP پرداخت در محدوده آدرس های معتبر اعلام شده توسط پذيرنده نیست .لطفا مسئول فنی پذيرنده با بانک تماس حاصل فرمايند';
					break;
				case 1030:
					$message = 'آدرس Domain پرداخت در محدوده آدرس های معتبر اعلام شده توسط پذيرنده نیست .لطفا مسئول فنی پذيرنده با بانک تماس حاصل فرمايند';
					break;
				case 1031:
					$message = 'مهلت زمانی شما جهت پرداخت به پايان رسیده است.لطفا مجددا سعی بفرمايید .';
					break;
				case 1032:
					$message = 'پرداخت با اين کارت. برای پذيرنده مورد نظر شما امکان پذير نیست.لطفا از کارتهای مجاز که توسط پذيرنده معرفی شده است . استفاده نمايید.';
					break;
				case 1033:
					$message = 'به علت مشکل در سايت پذيرنده. پرداخت برای اين پذيرنده غیرفعال شده است.لطفا مسوول فنی سايت پذيرنده با بانک تماس حاصل فرمايند.';
					break;
				case 1036:
					$message = 'اطلاعات اضافی ارسال نشده يا دارای اشکال است';
					break;
				case 1037:
					$message = 'شماره پذيرنده يا شماره ترمینال پذيرنده صحیح نمیباشد';
					break;
				case 1053:
					$message = 'خطا: درخواست معتبر, از سمت پذيرنده صورت نگرفته است لطفا اطلاعات پذيرنده خود را چک کنید.';
					break;
				case 1055:
					$message = 'مقدار غیرمجاز در ورود اطلاعات';
					break;
				case 1056:
					$message = 'سیستم موقتا قطع میباشد.لطفا بعدا تلاش فرمايید.';
					break;
				case 1058:
					$message = 'سرويس پرداخت اينترنتی خارج از سرويس می باشد.لطفا بعدا سعی بفرمايید.';
					break;
				case 1061:
					$message = 'اشکال در تولید کد يکتا. لطفا مرورگر خود را بسته و با اجرای مجدد مرورگر « عملیات پرداخت را انجام دهید )احتمال استفاده از دکمه Back » مرورگر(';
					break;
				case 1064:
					$message = 'لطفا مجددا سعی بفرمايید';
					break;
				case 1065:
					$message = 'ارتباط ناموفق .لطفا چند لحظه ديگر مجددا سعی کنید';
					break;
				case 1066:
					$message = 'سیستم سرويس دهی پرداخت موقتا غیر فعال شده است';
					break;
				case 1068:
					$message = 'با عرض پوزش به علت بروزرسانی . سیستم موقتا قطع میباشد.';
					break;
				case 1072:
					$message = 'خطا در پردازش پارامترهای اختیاری پذيرنده';
					break;
				case 1101:
					$message = 'مبلغ تراکنش نامعتبر است';
					break;
				case 1103:
					$message = 'توکن ارسالی نامعتبر است';
					break;
				case 1104:
					$message = 'اطلاعات تسهیم صحیح نیست';
					break;
				default:
					$message = 'خطای تعریف نشده رخ داده است .';
			}

			return $message;
		}
	}
endif;