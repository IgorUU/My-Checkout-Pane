<?php

namespace Drupal\my_checkout_pane\Plugin\Commerce\CheckoutPane;

use Drupal\commerce_checkout\Plugin\Commerce\CheckoutPane\CheckoutPaneBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a custom message pane.
 *
 * @CommerceCheckoutPane(
 *  id = "my_checkout_pane_custom_message",
 *  label = @Translation("Custom message"),
 * )
 */
class CustomMessagePane extends CheckoutPaneBase {

  public function buildPaneForm(array $pane_form, FormStateInterface $form_state, array &$complete_form)
  {

      $orderItem = $this->order->getItems();
      $difference = 0;

      foreach($orderItem as $item) {
        $productVariation = $item->getPurchasedEntity();

        // List Price BIGGER
        $productVariation_listPrice = $productVariation->get('list_price')->getValue();
        $productVariation_listPrice = reset($productVariation_listPrice);
        $list_price = $productVariation_listPrice['number'];

        if(!$list_price == null) {

          // Quantity
          $quantity = 0;
          $quantity = $quantity + $item->getQuantity();

          // Price
          $productVariation_price = $productVariation->get('price')->getValue();
          $productVariation_price = reset($productVariation_price);
          $price = $productVariation_price['number'];

          $difference += ($list_price - $price ) * $quantity ;
        }
      }

      $pane_form['message'] = [
        '#markup' => $this->t('You saved: '.$difference.' EUR!')
      ];
      return $pane_form;
  }

}
