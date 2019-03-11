<?php

namespace Drupal\pidramble_analytics\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Provides an analytics block.
 *
 * @Block(
 *   id = "pidramble_analytics",
 *   admin_label = @Translation("Pi Dramble Analytics"),
 * )
 */
class PiDrambleAnalyticsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
      '#type' => 'inline_template',
      '#context' => array(),
      '#template' => "<!-- Fathom - simple website analytics - https://github.com/usefathom/fathom -->
<script>
(function(f, a, t, h, o, m){
  a[h]=a[h]||function(){
    (a[h].q=a[h].q||[]).push(arguments)
  };
  o=f.createElement('script'),
  m=f.getElementsByTagName('script')[0];
  o.async=1; o.src=t; o.id='fathom-script';
  m.parentNode.insertBefore(o,m)
})(document, window, '//analytics.midwesternmac.com/tracker.js', 'fathom');
fathom('set', 'siteId', 'KMISI');
fathom('trackPageview');
</script>
<!-- / Fathom -->",
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    // Only allow access if PIDRAMBLE_ANALYTICS_ENABLED is true.
    if (getenv('PIDRAMBLE_ANALYTICS_ENABLED')) {
      return AccessResult::allowed();
    }
    return AccessResult::forbidden();
  }

}
