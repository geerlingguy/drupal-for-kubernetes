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

    // Create menu links after default content import is complete.
    $this->createMenuLinks();
  }

  /**
   * Custom function to create menu links, until default_content can export them.
   *
   * @see https://www.drupal.org/project/default_content/issues/2885285
   * @see https://www.drupal.org/project/drupal/issues/2577923
   */
  private function createMenuLinks() {
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

}
