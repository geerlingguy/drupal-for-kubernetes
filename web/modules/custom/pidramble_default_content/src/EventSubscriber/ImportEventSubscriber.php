<?php

namespace Drupal\pidramble_default_content\EventSubscriber;

use Drupal\default_content\Event\ImportEvent;
use Drupal\default_content\Event\DefaultContentEvents;
use Drupal\menu_link_content\Entity\MenuLinkContent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ImportEventSubscriber.
 *
 * @package Drupal\pidramble_default_content\EventSubscriber
 */
class ImportEventSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      DefaultContentEvents::IMPORT => ['onDefaultContentImport'],
    ];
  }

  /**
   * Default content import event handler.
   *
   * @param \Drupal\default_content\Event\ImportEvent $event
   *   Response event.
   */
  public function onDefaultContentImport(ImportEvent $event) {
    $entities = $event->getImportedEntities();

    // Create main menu links after default content import is complete.
    $this->createMainMenuLinks();

    // Create wiki menu and menu links after default content import is complete.
    $this->createWikiMenu();
    $this->createWikiMenuLinks();
  }

  /**
   * Create main menu links (needed until default_content can export them).
   *
   * @see https://www.drupal.org/project/default_content/issues/2885285
   * @see https://www.drupal.org/project/drupal/issues/2577923
   */
  private function createMainMenuLinks() {
    $items = [
      [
        'uri' => 'route:<front>',
        'title' => 'Home',
        'weight' => 0,
      ],
      [
        'uri' => 'internal:/node/5',
        'title' => 'Wiki',
        'weight' => 5,
      ],
      [
        'uri' => 'internal:/node/2',
        'title' => 'About',
        'weight' => 10,
      ],
    ];

    foreach($items as $item) {
      $menu_link = MenuLinkContent::create([
        'title' => $item['title'],
        'link' => ['uri' => $item['uri']],
        'weight' => $item['weight'],
        'menu_name' => 'main',
        'expanded' => TRUE,
      ]);
      $menu_link->save();
    }
  }

  /**
   * Create Wiki menu (needed until default_content can export them).
   *
   * @see https://www.drupal.org/project/default_content/issues/2885285
   * @see https://www.drupal.org/project/drupal/issues/2577923
   */
  private function createWikiMenu() {
    // Yeah, I should inject my dependencies. Sue me.
    \Drupal::entityTypeManager()
      ->getStorage('menu')
      ->create([
        'id' => 'wiki',
        'label' => 'Wiki',
        'description' => 'Wiki links.',
      ])
      ->save();
    \Drupal::service("router.builder")
      ->rebuild();
  }

  /**
   * Create main menu links (needed until default_content can export them).
   *
   * @see https://www.drupal.org/project/default_content/issues/2885285
   * @see https://www.drupal.org/project/drupal/issues/2577923
   */
  private function createWikiMenuLinks() {
    $items = [
      [
        'uri' => 'internal:/node/6',
        'title' => 'Appearances and Presentations',
        'weight' => 0,
        'top_level' => TRUE,
      ],

      // Hardware and Parts section.
      [
        'uri' => 'internal:/node/7',
        'title' => 'Hardware and Parts',
        'weight' => 5,
        'top_level' => TRUE,
      ],
      // Children.
      [
        'uri' => 'internal:/node/8',
        'title' => 'Raspberry Pis and Accessories',
        'weight' => 0,
        'top_level' => FALSE,
      ],
      [
        'uri' => 'internal:/node/9',
        'title' => 'Other Necessities',
        'weight' => 1,
        'top_level' => FALSE,
      ],
      [
        'uri' => 'internal:/node/10',
        'title' => 'RGB LEDs controlled via GPIO',
        'weight' => 2,
        'top_level' => FALSE,
      ],
      [
        'uri' => 'internal:/node/11',
        'title' => 'Recommended Extras',
        'weight' => 3,
        'top_level' => FALSE,
      ],
      [
        'uri' => 'internal:/node/12',
        'title' => 'Tips and Tricks',
        'weight' => 4,
        'top_level' => FALSE,
      ],

      // Setup section.
      [
        'uri' => 'internal:/node/14',
        'title' => 'Setup',
        'weight' => 10,
        'top_level' => TRUE,
      ],
      // Children.
      [
        'uri' => 'internal:/node/13',
        'title' => 'Prepare the Raspberry Pis',
        'weight' => 0,
        'top_level' => FALSE,
      ],
      [
        'uri' => 'internal:/node/15',
        'title' => 'Rack the Raspberry Pis',
        'weight' => 1,
        'top_level' => FALSE,
      ],
      [
        'uri' => 'internal:/node/16',
        'title' => 'Network the Raspberry Pis',
        'weight' => 2,
        'top_level' => FALSE,
      ],
      [
        'uri' => 'internal:/node/17',
        'title' => 'Test Ansible configuration',
        'weight' => 3,
        'top_level' => FALSE,
      ],
      [
        'uri' => 'internal:/node/18',
        'title' => 'Provision the Raspberry Pis',
        'weight' => 4,
        'top_level' => FALSE,
      ],
      [
        'uri' => 'internal:/node/19',
        'title' => 'Deploy Drupal to the Raspberry Pis',
        'weight' => 5,
        'top_level' => FALSE,
      ],
      [
        'uri' => 'internal:/node/20',
        'title' => 'Fail over the MySQL database',
        'weight' => 6,
        'top_level' => FALSE,
      ],
      [
        'uri' => 'internal:/node/21',
        'title' => 'Test with Vagrant VMs Locally',
        'weight' => 7,
        'top_level' => FALSE,
      ],

      // Benchmarks section.
      [
        'uri' => 'internal:/node/22',
        'title' => 'Benchmarks',
        'weight' => 15,
        'top_level' => TRUE,
      ],
      // Children.
      [
        'uri' => 'internal:/node/23',
        'title' => 'microSD Card Benchmarks',
        'weight' => 0,
        'top_level' => FALSE,
      ],
      [
        'uri' => 'internal:/node/24',
        'title' => 'Networking Benchmarks',
        'weight' => 1,
        'top_level' => FALSE,
      ],
      [
        'uri' => 'internal:/node/25',
        'title' => 'Drupal Benchmarks',
        'weight' => 2,
        'top_level' => FALSE,
      ],
      [
        'uri' => 'internal:/node/26',
        'title' => 'External USB drives',
        'weight' => 3,
        'top_level' => FALSE,
      ],
      [
        'uri' => 'internal:/node/27',
        'title' => 'Power Consumption Benchmarks',
        'weight' => 4,
        'top_level' => FALSE,
      ],
    ];

    $parent = '';
    foreach($items as $item) {
      // For top-level items, store the parent ID for the children to use.
      if ($item['top_level']) {
        $menu_link = MenuLinkContent::create([
          'title' => $item['title'],
          'link' => ['uri' => $item['uri']],
          'weight' => $item['weight'],
          'menu_name' => 'wiki',
          'expanded' => TRUE,
        ]);
        $menu_link->save();
        $parent = 'menu_link_content:' . $menu_link->uuid();
      }
      else {
        $menu_link = MenuLinkContent::create([
          'title' => $item['title'],
          'link' => ['uri' => $item['uri']],
          'weight' => $item['weight'],
          'menu_name' => 'wiki',
          'expanded' => TRUE,
          'parent' => $parent,
        ]);
        $menu_link->save();
      }
    }
  }

}
