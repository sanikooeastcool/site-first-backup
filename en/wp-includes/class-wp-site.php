<?php
/**
 * Site API: WP_Site class
 *
 * @package WordPress
 * @subpackage Multisite
 * @since 4.5.0
 */

/**
 * Core class used for interacting with a multisite site.
 *
 * This class is used during load to populate the `$current_blog` global and
 * setup the current site.
 *
 * @since 4.5.0
 *
 * @property int    $id
 * @property int    $network_id
 * @property string $blogname
 * @property string $siteurl
 * @property int    $post_count
 * @property string $home
 */
final class WP_Site {

	/**
	 * Site ID.
	 *
	 * Named "blog" vs. "site" for legacy reasons.
	 *
	 * A numeric string, for compatibility reasons.
	 *
	 * @since 4.5.0
	 * @var string
	 */
	public $blog_id;

	/**
	 * Domain of the site.
	 *
	 * @since 4.5.0
	 * @var string
	 */
	public $domain = '';

	/**
	 * Path of the site.
	 *
	 * @since 4.5.0
	 * @var string
	 */
	public $path = '';

	/**
	 * The ID of the site's parent network.
	 *
	 * Named "site" vs. "network" for legacy reasons. An individual site's "site" is
	 * its network.
	 *
	 * A numeric string, for compatibility reasons.
	 *
	 * @since 4.5.0
	 * @var string
	 */
	public $site_id = '0';

	/**
	 * The date and time on which the site was created or registered.
	 *
	 * @since 4.5.0
	 * @var string Date in MySQL's datetime format.
	 */
	public $registered = '0000-00-00 00:00:00';

	/**
	 * The date and time on which site settings were last updated.
	 *
	 * @since 4.5.0
	 * @var string Date in MySQL's datetime format.
	 */
	public $last_updated = '0000-00-00 00:00:00';

	/**
	 * Whether the site should be treated as public.
	 *
	 * A numeric string, for compatibility reasons.
	 *
	 * @since 4.5.0
	 * @var string
	 */
	public $public = '1';

	/**
	 * Whether the site should be treated as archived.
	 *
	 * A numeric string, for compatibility reasons.
	 *
	 * @since 4.5.0
	 * @var string
	 */
	public $archived = '0';

	/**
	 * Whether the site should be treated as mature.
	 *
	 * Handling for this does not exist throughout WordPress core, but custom
	 * implementations exist that require the property to be present.
	 *
	 * A numeric string, for compatibility reasons.
	 *
	 * @since 4.5.0
	 * @var string
	 */
	public $mature = '0';

	/**
	 * Whether the site should be treated as spam.
	 *
	 * A numeric string, for compatibility reasons.
	 *
	 * @since 4.5.0
	 * @var string
	 */
	public $spam = '0';

	/**
	 * Whether the site should be treated as deleted.
	 *
	 * A numeric string, for compatibility reasons.
	 *
	 * @since 4.5.0
	 * @var string
	 */
	public $deleted = '0';

	/**
	 * The language pack associated with this site.
	 *
	 * A numeric string, for compatibility reasons.
	 *
	 * @since 4.5.0
	 * @var string
	 */
	public $lang_id = '0';

	/**
	 * Retrieves a site from the database by its ID.
	 *
	 * @since 4.5.0
	 *
	 * @global wpdb $wpdb WordPress database abstraction object.
	 *
	 * @param int $site_id The ID of the site to retrieve.
	 * @return WP_Site|false The site's object if found. False if not.
	 */
	public static function get_instance( $site_id ) {
		global $wpdb;

		$site_id = (int) $site_id;
		if ( ! $site_id ) {
			return false;
		}

		$_site = wp_cache_get( $site_id, 'sites' );

		if ( false === $_site ) {
			$_site = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->blogs} WHERE blog_id = %d LIMIT 1", $site_id ) );

			if ( empty( $_site ) || is_wp_error( $_site ) ) {
				$_site = -1;
			}

			wp_cache_add( $site_id, $_site, 'sites' );
		}

		if ( is_numeric( $_site ) ) {
			return false;
		}

		return new WP_Site( $_site );
	}

	/**
	 * Creates a new WP_Site object.
	 *
	 * Will populate object properties from the object provided and assign other
	 * default properties based on that information.
	 *
	 * @since 4.5.0
	 *
	 * @param WP_Site|object $site A site object.
	 */
	public function __construct( $site ) {
		for D.
	 *
	 * @o ! $site_id ) ct.
	__pubs D.
	 *
	  * $key => $valu $wpdb->ge* A ->$key = $valu ew WP_tes a new WP_Sonvhatbje @param  @rearray4.5.0
	 *
	 * @para6 WP_Site|obje( $_siarray Oaram  	 * Aray4.5.0n __construct( $sitto_ Aray(wpdb->e( $_sid ) ct.
	__pubs D.* A nreates a new WP_G uperic string, llows
 * @sinc*
 * This  st notconvhnrequirwhesid )site on that in.ring, llows
accract@reextenher
This on that in.ring
	 *
	 * @para6 WP_Site|object $sfinal clkey Psent.
	 *
	d )Site|false Themixer
Valu $trieve.esent.
	. Nut plic st availablenction __construct( $site ites' key wpdb->s *
ite_i key wpdb->	cbjec'id':
			}lse The	if ( !* A ->* Domain ->	cbjec'operty str':
			}lse The	if ( !* A -> $site_id )	cbjec'erty str':
			cbjec'rty int':
			cbjec'operty str':
			cbjec'ass ':
			s based: // Cntati on that infsiter
* @'rty _detail isfilperic 	 ) {
			rdid_*
	 * ( 'ms_the ed' = -1;
			}te( $_situt id )	dd( $si	$detail  = $* A ->d ) detail ();c 	 ) {
		isstes' detail ->$key = -1;
			}te( $_si detail ->$keyid )	dd( WP_Site( $_situt id es a new WP_Isste-eric string, llows
 * @sinc*
 * This  st notconvhnrequirwhesi, $ck not exion that in.ring,C $ckst exiextenher
This on that in.ring
	 *
	 * @para6 WP_Site|object $sfinal clkey Psent.
	 *
	, $ckplics )Site|false Thebool should be trpost_countscs )Site|n __construct( $site isstes' key wpdb->s *
ite_i key wpdb->	cbjec'id':
			cbjec'operty str':
			}lse The		feid )	cbjec'erty str':
			cbjec'rty int':
			cbjec'operty str':
			cbjec'ass ':
			) {
			rdid_*
	 * ( 'ms_the ed' = -1;
			}te( $_siurn new W	dd( W	}lse The		feid )	s based: // Cntati on that infsiter
* @'rty _detail isfilperic 	 ) {
			rdid_*
	 * ( 'ms_the ed' = -1;
			}te( $_siurn new W	dd( $si	$detail  = $* A ->d ) detail ();c 	 ) {
		isstes' detail ->$key = -1;
			}te( $_si		feid )	dd( WP_Site( $_siurn new es a new WP_S uperic string, llows
 * @sinc*
 * This  st notconvhnrequirwhilast updat on that in.ring
	 *
	 * @para6 WP_Site|object $sfinal clkey   Psent.
	 *
	s )Site|faect $smixer
 $valu $Valu $@reaefault@reeve.esent.
	.ite|n __construct( $site stes' key, $valu $wpdb->s *
ite_i key wpdb->	cbjec'id':
			}!* A ->* Domai;
		final ) $valu ew W		b $skn ->	cbjec'operty str':
			}!* A -> $site_;
		final ) $valu ew W		b $skn ->	s based:
			}!* A ->$key = $valu ew WP_tes a new WP_from the dts IDetail   exist thumeric string,d durmoulodoad to poa mulnall	 *
	lazy-the `$s Iextenher
on that inftriaince 4.5.0
	 * @var stri6 WP_Site|objseesite A s::e ites)P_Site|obje( $_sistdC/**
	A raw/
	public fu site.
t pDetail  ar luderic stn __rivtiesance( $site_idetail ()pdb->$detail  = te_id, 'sites' * A ->* Domai
		if (-detail is=== $_site ) {
			$_sidetail  ) ID.
->s *
it_to_* Dos' * A ->* Domais_nume	// Cite oiairaw/copy$trieve.lic fu  exibth wardasons.
	 *
	 *  *
	 * esfilper belowic 	 $detail  = t.
	stdC/**
();c 	  ! $site_id ) ct.
	__pubs D.* A nr  * $key => $valu $wpdb->g	 detail ->$key = $valu ew Wdd( W	 detail ->erty str   =id ) cp	 * ( 'erty str's_nume	 detail ->rty int    =id ) cp	 * ( 'rty int's_nume	 detail ->operty str =id ) cp	 * ( 'operty str's_nume	 detail ->ass        =id ) cp	 * ( 'ass 's_nume	retatre_bal and
 * s(( $_sitte_id, 'sstes' * A ->* Domai
	 detail 
		if (-detail is=== WP_Sit ne,d durfilper  throcuthat poa  te-ar ludes/ms-_id =.phpstn _ $detail  = apply_filperside* Fcte.
( 'erty_detail i,* Aray(sidetail  )
		4.7.0',@'rty _detail is( $_si new e|obFilpersiaince 'sIextenher
on that in.w e|ow e|ob@var stri6 WP_e|ow e|ob@ect $sfidC/**
	idetail   if founpDetail .w e|on _ $detail  = apply_filpers(@'rty _detail i,sidetail  );_Site( $_siidetail ew es}
