                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                // =============================================================================
// SCSS/SITE/STACKS/ICON/_WOOCOMMERCE.SCSS
// -----------------------------------------------------------------------------
// Contains styles for WooCommerce.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Shop Styles
//   02. Product Styles
//   03. Product Loops
//   04. Cart and Collaterals
//   05. Cart Quantity Form
//   06. Cart Actions
//   07. Shipping Method
//   08. Account and Checkout
//   09. Product Name Variation
//   10. Widgets
//   11. Columns
//   12. Popups
//   13. Price
//   14. Sale Badge
//   15. Star Rating
//   16. Review Form
//   17. Results Count and Ordering
//   18. Messages and Errors
//   19. Form Feedback States
//   20. Button Icons
// =============================================================================

// Shop Styles
// =============================================================================

.woocommerce,
.woocommerce-page {
  .entry-header.shop {
    margin-bottom: 50px;
    @include break(cubs) {
      margin-bottom: 32px;
    }
    @include break(baby-bear) {
      margin-bottom: 24px;
    }
  }

  ul.products {
    clear: both;
    margin: 0;
    list-style: none;
    @include clearfix();
  }

  li.product {
    float: left;
    overflow: hidden;
    position: relative;
    margin: 0 4% 55px 0;
    border: 0;
    padding: 0;

    &.first {
      clear: both;
    }

    &.last {
      margin-right: 0;
    }

    .entry-featured {
      overflow: hidden;
      margin-top: 0;
      z-index: 0;

      a {
        display: block;
      }

      img {
        min-width: 100%;
      }
    }

    .entry-wrap {
      border: 0;
      padding: 15px 0 0;
    }

    .entry-header {
      position: relative;

      h3 {
        margin: 0;
        padding-bottom: 0.5em;
        font-size: 148%;
        line-height: 1.05;
        @include transition(#{color 0.3s ease});

        a:hover {
          color: $accentColor;
        }
      }

      .price {
        > .amount,
        > ins > .amount {
          @include font-size(1.8);
        }
      }

      .button {
        position: absolute;
        top: -65px;
        left: 17px;
        right: 17px;
        margin: 0;
        padding: 0.45em 0.5em 0.575em;
        display: block;
        @include font-size(1.4);
        @include opacity(0);
        @include text-overflow();
      }
    }

    .added_to_cart {
      display: none;
    }

    &:hover {
      .star-rating-container {
        @include opacity(1);
      }

      .entry-header {
        .button {
          @include opacity(1);
        }
      }
    }
  }
}



// Product Styles
// =============================================================================

.woocommerce,
.woocommerce-page {
  .entry-content:first-child {
    margin: 0;
    padding: 0;
    @include clearfix();
  }

  div.product {
    border: 0;
    padding: 0;
    @include clearfix();

    .images {
      position: relative;
      overflow: hidden;
      float: left;
      width: 48%;
      @include break(middle-bear) {
        width: 100%;
      }

      .x-img-thumbnail:hover {
        border-color: $baseBorderSolid;
        border-color: $baseBorderRgba;
      }

      .thumbnails {
        > a {
          display: block;
          float: left;
          width: 22%;
          margin: 4% 4% 0 0;

          &:nth-child(4n+4) {
            margin-right: 0;
          }
        }
      }
    }

    .summary {
      float: right;
      clear: right;
      width: 48%;
      @include break(middle-bear) {
        width: 100%;
        margin-top: 50px;
      }

      .product_title {
        margin: 0 0 0.25em;
        padding: 0;
        font-size: 228%;
        text-align: left;
        @include break(baby-bear) {
          font-size: 200%;
        }
      }

      .price {
        > del,
        > .from {
          @include font-size(1.8);
        }

        > .amount,
        > ins > .amount {
          @include font-size(2.4);
        }
      }

      .variations {
        margin-bottom: $baseMargin;

        .label,
        .value {
          vertical-align: middle;
          border-top: 0;
          background-color: transparent;
        }

        .label {
          padding-left: 0;
        }

        .value {
          padding-right: 0;
        }

        select {
          width: 100%;
          margin-bottom: 0;
        }

        .reset_variations {
          display: none;
        }
      }

      .single_variation {
        text-align: right;

        .price {
          margin-bottom: $baseMargin;
        }
      }
    }

    .woocommerce-tabs {
      clear: both;
      float: left;
      width: 100%;
      margin-top: 50px;

      .x-tab-content {
        margin-bottom: 0;
      }

      h2 {
        margin-top: 0;
        margin-bottom: 0.5em;
        font-size: 200%;
      }

      table {
        margin-bottom: 0;
      }

      p:last-child {
        margin-bottom: 0;
      }
    }
  }
}



// Product Loops
// =============================================================================

.woocommerce,
.woocommerce-page {
  .upsells,
  .related,
  .cross-sells {
    clear: both;
    float: left;
    width: 100%;
    margin: 50px 0 -55px;

    h2 {
      margin: 0 0 0.5em;
      font-size: 200%;
    }
  }
}



// Cart and Collaterals
// =============================================================================

.woocommerce,
.woocommerce-page {
  .cart-form {
    margin: 0;
  }

  .cart {
    margin-top: $baseMargin;

    &.shop_table {
      margin: 0;

      .product-thumbnail {
        img {
          width: 50%;
        }
      }

      .product-name {
        @include break(middle-bear) {
          display: none;
        }
      }

      .product-price {
        @include break(cubs) {
          display: none;
        }
      }
    }
  }

  .cart-collaterals {
    .cart_totals {
      clear: both;
      float: left;
      width: 100%;
      margin: 50px 0 0;
    }

    table {
      margin: 0;

      th {
        width: 35%;
      }

      td {
        .x-alert {
          margin: 0.25em 0 0.35em;
        }
      }
    }

    .woocommerce-shipping-calculator {
      margin: 0;

      .shipping-calculator-button {
        display: inline-block;
        margin: 0.25em 0;
      }

      .form-row {
        margin: 0;

        &:first-child {
          margin-top: 0.5em;
        }
      }

      select,
      input[type="text"] {
        width: 100%;
      }
    }
  }

  .wc-proceed-to-checkout {
    margin: 50px 0 0;
    text-align: center;
  }
}



// Cart Quantity Form
// =============================================================================

.woocommerce,
.woocommerce-page {
  .quantity {
    margin-bottom: $baseMargin;

    input[type="number"] {
      width: 2.65em;
      margin: 0;
      text-align: center;
      -moz-appearance: textfield;
    }

    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
      margin: 0;
      -webkit-appearance: none;
    }
  }

  .cart_item {
    .quantity {
      margin-bottom: 0;
    }
  }
}



// Cart Actions
// =============================================================================

.woocommerce,
.woocommerce-page {
  .cart {
    .actions {
      padding: 0;

      .action-group {
        width: 200px;
        margin-top: -1px;
        border: 1px solid $baseBorderSolid;
        border: 1px solid $baseBorderRgba;
        padding: 15px;
        background-color: $baseModBackground;
        @include break(middle-bear) {
          width: 100%;
        }

        label {
          display: none;
        }

        input {
          width: 100%;
        }

        input[type="text"] {
          margin-bottom: 0;
        }

        input[type="submit"] {
          margin-top: 0.5em;
          padding: 0.45em 0.5em 0.575em;
          display: block;
          @include font-size(1.4);

          &:first-child {
            margin-top: 0;
          }
        }

        &.coupon {
          float: left;
        }

        &.update {
          float: right;
        }
      }
    }
  }
}



// Shipping Method
// =============================================================================

.woocommerce,
.woocommerce-page {
  #shipping_method {
    margin-left: 0;
    list-style: none;
    @include clearfix();

    li {
      float: left;
      width: 100%;
      display: block;
    }

    label {
      display: inline-block;
      position: relative;
      top: 0.05em;
      margin: 0 0 0 0.2em;
    }
  }

  select.shipping_method {
    width: 100%;
    margin: 0.25em 0 $baseMargin;

    &:last-child {
      margin-bottom: 0.25em;
    }
  }
}



// Account and Checkout
// =============================================================================

.woocommerce-account,
.woocommerce-checkout {
  .woocommerce {
    h2:first-child {
      margin-top: 0;
    }

    header {
      h2:first-child {
        margin-top: 60px;
      }
    }
  }

  .myaccount_user {
    margin-bottom: 0;
  }


  //
  // Billing information.
  //

  .checkout_coupon {
    margin-bottom: 1.85em;
    @include clearfix();

    .form-row-first,
    .form-row-last {
      float: none;
      width: 100%;
    }

    .form-row-first {
      margin-bottom: 0.5em;

      input {
        width: 100%;
        margin-bottom: 0;
      }
    }

    .form-row-last {
      margin-bottom: 0;
    }
  }

  .checkout,
  .form-edit-address {
    margin-bottom: 0;

    h3 {
      margin-top: 60px;
      font-size: 164%;

      &:first-child {
        margin-top: 0;
      }

      &.ship-to-different-address {
        margin-top: 60px;
 
        .input-checkbox {
          margin: 0.575em 0 0;
        }
      }
    }

    abbr {
      border: 0;
    }

    select,
    textarea,
    input[type="text"] {
      width: 100%;
    }

    textarea {
      height: 6em;
      resize: vertical;
    }

    input[name="billing_address_1"] {
      margin-bottom: 0;
    }

    input[type="radio"],
    input[type="checkbox"] {
      float: left;
    }

    .woocommerce-billing-fields,
    .woocommerce-shipping-fields {
      @include clearfix();
    }

    .form-row {
      margin-bottom: 0.65em;
      @include clearfix();

      &.terms {
        margin: 1em 0 0;

        label {
          float: left;
          margin: 0 5px 0 0;
          padding: 0;
        }
      }
    }

    .form-row-first,
    .form-row-last {
      float: left;
      width: 48%;
    }

    .form-row-first {
      margin-right: 4%;
    }

    .payment_methods {
      margin-left: 0;
      list-style: none;

      li {
        margin-bottom: $baseMargin;
      }

      label {
        padding-left: 18px;

        img {
          display: none;
        }
      }

      p {
        font-size: 0.75em;
        line-height: 1.4;
      }
    }

    .place-order {
      margin-bottom: 0;
    }
  }


  //
  // Order received.
  //

  ul.order_details {
    margin-left: 0;
    list-style: none;
  }

  table.order_details {
    dl {
      margin: 0;
    }
  }

  h2 {
    margin-top: 65px;
    font-size: 170%;
  }

  h3 {
    margin-top: 0;
    font-size: 100%;
  }

  .addresses {
    .col-1,
    .col-2 {
      float: left;
      width: 48%;

      address {
        margin-bottom: 0;
      }
    }

    .col-1 {
      margin-right: 4%;
    }
  }


  //
  // View order.
  //

  .my_account_orders {
    margin: 10px 0 0;

    @include break(middle-bear) {
      .order-date,
      .order-total {
        display: none;
      }
    }
  }


  //
  // Change password.
  //

  .change_password {
    margin-bottom: 0;

    p {
      &.form-row-first {
        margin-bottom: 0;
      }

      &:last-of-type {
        margin-bottom: 0;
      }

      input[type="password"] {
        width: 100%;
      }
    }
  }


  //
  // Lost password.
  //

  .lost_reset_password {
    margin-bottom: 0;

    p {
      &:last-of-type {
        margin-bottom: 0;
      }

      input[type="text"] {
        width: 100%;
      }
    }
  }
}



// Product Name Variation
// =============================================================================

//
// Found in cart table and review order table.
//

.woocommerce,
.woocommerce-page {
  .product-name {
    .variation {
      margin-bottom: 0;

      dt,
      dd {
        margin: 0;
      }
    }
  }
}



// Widgets
// =============================================================================

//
// Widgets with images.
//

.widget_best_sellers,
.widget_shopping_cart,
.widget_products,
.widget_featured_products,
.widget_onsale,
.widget_random_products,
.widget_recently_viewed_products,
.widget_recent_products,
.widget_recent_reviews,
.widget_top_rated_products {
  ul {
    li {
      @include clearfix();
      font-size: 78.5%;

      &:last-child {
        margin-bottom: 0;
      }

      a {
        display: block;
        margin-bottom: 2px;
        border-bottom: 0;
        font-size: 118%;
        line-height: 1.3;

        img {
          @include img_thumbnail();
          float: left;
          width: 68px;
          margin-right: 0.65em;
          padding: 3px !important;
        }
      }

      > del,
      > ins {
        text-shadow: none;
      }
    }
  }
}


//
// Cart.
//

.widget_shopping_cart {
  .empty {
    text-align: center;
  }

  ul {
    margin-bottom: -1px;

    li {
      position: relative;

      .remove {
        display: block;
        position: absolute;
        right: 0;
        bottom: 0;
        margin: 0;
        width: 25px;
        height: 25px;
        @include font-size(1.8);
        line-height: 25px;
        text-align: center;
        opacity: 0;
        @include transition(#{opacity 0.3s ease});
      }

      &:hover {
        .remove {
          opacity: 0.35;

          &:hover {
            opacity: 1;
          }
        }
      }
    }
  }

  .total {
    margin: 0 -21px;
    border: 1px solid $baseBorderSolid;
    border: 1px solid $baseBorderRgba;
    border-top: 0;
    border-bottom: 0;
    padding: 7px;
    @include font-size(1.1);
    line-height: 1.1;
    text-align: center;
    text-transform: uppercase;
    background-color: $baseModBackground;
  }

  .buttons {
    margin: 0 -21px;
    border: 1px solid $baseBorderSolid;
    border: 1px solid $baseBorderRgba;
    background-color: $baseModBackground;
    @include clearfix();

    .button {
      float: left;
      width: 50%;
      margin: 0;
      border: 0;
      padding: 7px;
      @include font-size(1.1);
      line-height: 1.1;
      text-align: center;
      text-shadow: none;
      color: $textColor;
      background-color: transparent;
      @include box-shadow(#{none});
      @include text-overflow();

      &:hover {
        background-color: #fafafa;
      }

      &:first-child {
        border-right: 1px solid $baseBorderSolid;
        border-right: 1px solid $baseBorderRgba;
      }
    }
  }
}


//
// Layered nav and layered nav filters.
//

.widget_layered_nav {
  li {
    position: relative;

    .count {
      position: absolute;
      top: 50%;
      right: 20px;
      width: 24px;
      height: 24px;
      margin-top: -12px;
      line-height: 24px;
      text-align: center;
      background-color: $widgetBorderColor;
      border-radius: 100%;
    }
  }
}


//
// Price filter.
//

.widget_price_filter {
  form {
    margin-bottom: 0;

    input[type="text"] {
      display: none;
    }
  }

  .price_slider_wrapper {
    @include clearfix();
  }

  .ui-slider {
    position: relative;
    height: 8px;
    margin: 10px 0 24px;
    border-radius: 1em;
    background-color: $shortcodeBorderColor;

    .ui-slider-handle {
      position: absolute;
      top: 50%;
      width: 21px;
      height: 21px;
      margin-top: -11px;
      cursor: pointer;
      background-color: $headingsColor;
      outline: none;
      border-radius: 100%;
      z-index: 2;

      &:last-child {
        margin-left: -19px;
      }
    }

    .ui-slider-range {
      display: block;
      position: absolute;
      top: 0;
      height: 100%;
      border: 0;
      background-color: $linkColor;
      border-radius: 1em;
      z-index: 1;
      @include box-shadow(#{none});
    }
  }

  .price_slider_amount {
    @include clearfix();

    .button,
    .price_label {
      float: left;
      width: 50%;
      margin: 0;
      border: 1px solid $widgetBorderColor;
      padding: 7px;
      @include font-size(1.1);
      line-height: 1.1;
      text-align: center;
      text-shadow: none;
      background-color: transparent;
      @include box-shadow(#{none});
      @include text-overflow();
    }

    .button {
      color: $textColor;

      &:hover {
        background-color: #fafafa;
      }
    }

    .price_label {
      width: 50%;
      border-left: 0;
    }
  }
}


//
// Product search.
//

.widget_product_search {
  input[type="submit"] {
    display: none;
  }
}


//
// Reviews and top rated products.
//

.widget.widget_recent_reviews,
.widget.widget_top_rated_products {
  .star-rating {
    width: 5.8em;
    margin-bottom: 2px;
  }
}



// Columns
// =============================================================================

.woocommerce {
  .cols-1,
  .cols-2,
  .cols-3,
  .cols-4,
  &.columns-1,
  &.columns-2,
  &.columns-3,
  &.columns-4 {
    @include clearfix();
  }

  .cols-1, &.columns-1 { li.product { width: 100%;      } }
  .cols-2, &.columns-2 { li.product { width: 48%;       } }
  .cols-3, &.columns-3 { li.product { width: 30.66667%; } }
  .cols-4, &.columns-4 { li.product { width: 22%;       } }

  .cols-3,
  .cols-4,
  &.columns-3,
  &.columns-4 {
    li.product {
      @include break(cubs) {
        width: 48%;

        &.first           { clear: none;      }
        &.last            { margin-right: 4%; }
        &:nth-child(2n+3) { clear: both;      }
        &:nth-child(2n+2) { margin-right: 0;  }
      }
    }
  }

  .cols-2,
  .cols-3,
  .cols-4,
  &.columns-2,
  &.columns-3,
  &.columns-4 {
    li.product {
      @include break(baby-bear) {
        width: 100%;
      }
    }
  }
}



// Popups
// =============================================================================

.woocommerce,
.woocommerce-page {
  .pp_woocommerce {
    .ppt {
      visibility: hidden;
    }

    .pp_content_container {
      padding-top: 40px;
      padding-bottom: 10px;
    }

    .pp_expand:before,
    .pp_contract:before {
      top: -1px;
      right: -1px;
    }

    .pp_nav {
      line-height: 1;
    }

    .pp_arrow_previous:before,
    .pp_arrow_next:before {
      top: -1px;
    }

    .pp_close:before {
      top: -1px;
    }

    .pp_description {
      visibility: hidden;
    }
  }
}



// Price
// =============================================================================

.woocommerce,
.woocommerce-page {
  .price {
    display: block;
    line-height: 1;
    @include clearfix();

    > .from,
    > del {
      color: $textColor;
    }

    > ins {
      text-decoration: none;
    }

    > .amount,
    > ins > .amount {
      color: $linkColor;
    }
  }
}



// Sale Badge
// =============================================================================

.woocommerce,
.woocommerce-page {
  .onsale {
    position: absolute;
    top: -29px;
    left: -63px;
    display: block;
    width: 150px;
    height: 80px;
    border: 1px solid $baseBorderSolid;
    border: 1px solid $baseBorderRgba;
    @include font-size(1.8);
    line-height: 120px;
    text-align: center;
    text-transform: uppercase;
    color: $linkColor;
    background-color: $baseModBackground;
    @include rotate(-45deg);
    z-index: 1;
  }
}

.ie8 {
  .woocommerce,
  .woocommerce-page {
    .onsale {
      top: 6px;
      left: 6px;
      width: 45px;
      height: 45px;
      line-height: 45px;
    }
  }
}



// Star Rating
// =============================================================================

.woocommerce,
.woocommerce-page {
  .star-rating-container {
    display: inline-block;
  }

  .star-rating {
    display: block;
    overflow: hidden;
    position: relative;
    float: none;
    width: 5.4em;
    height: 1em;
    margin: 0;
    font-size: 1em;
    font-style: normal !important;
    font-weight: normal !important;
    font-family: "star";
    line-height: 1.1em;

    &:before {
      content: "\73\73\73\73\73";
      position: absolute;
      top: 0;
      left: 0;
      float: left;
      color: $linkColor;
    }

    span {
      overflow: hidden;
      position: absolute;
      top: 0;
      left: 0;
      float: left;
      padding-top: 1.5em;

      &:before {
        content: "\53\53\53\53\53";
        position: absolute;
        top: 0;
        left: 0;
        color: $linkColor;
      }
    }
  }

  .x-comments-area {
    .star-rating-container.aggregate {
      @include font-size(2.1);
      margin-bottom: 8px;
    }
  }

  li.product {
    .star-rating-container {
      position: absolute;
      top: 14px;
      right: 14px;
      padding: 7px;
      background-color: $black;
      background-color: rgba(0, 0, 0, 0.25);
      @include opacity(0);
      @include translate3d(0, 0, 0);
      @include transition(#{opacity 0.3s ease});

      .star-rating {
        &:before {
          color: $white;
        }

        span {
          &:before {
            color: $white;
          }
        }
      }
    }
  }

  p.stars {
    @include clearfix();

    span {
      position: relative;
      float: left;

      a {
        float: left;
        padding-right: 5px;
        line-height: 1em;

        &.star-1,
        &.star-2,
        &.star-3,
        &.star-4,
        &.star-5 {
          &:after {
            display: none;
            width: 6em;
            border: 1px solid $baseBorderSolid;
            border: 1px solid $baseBorderRgba;
            padding: 0 0.5em;
            position: absolute;
            top: -1px;
            right: -8.25em;
            font-size: 0.5em;
            text-align: center;
            color: $textColor;
            background-color: $baseModBackground;
            @include font-awesome();
          }
        }

        &.star-1 {
          &:after {
            content: "\f005";
          }
        }

        &.star-2 {
          &:after {
            content: "\f005\0020\f005";
          }
        }

        &.star-3 {
          &:after {
            content: "\f005\0020\f005\0020\f005";
          }
        }

        &.star-4 {
          &:after {
            content: "\f005\0020\f005\0020\f005\0020\f005";
          }
        }

        &.star-5 {
          &:after {
            content: "\f005\0020\f005\0020\f005\0020\f005\0020\f005";
          }
        }

        &.active {
          &:after {
            display: block;
          }
        }

        &:hover,
        &:focus {
          &:after {
            display: block;
            z-index: 1;
          }
        }
      }
    }
  }
}

@font-face {
  font-family: "star";
  src: url("../../../../../../plugins/woocommerce/assets/fonts/star.eot");
  src: url("../../../../../../plugins/woocommerce/assets/fonts/star.eot?#iefix") format("embedded-opentype"),
       url("../../../../../../plugins/woocommerce/assets/fonts/star.woff") format("woff"),
       url("../../../../../../plugins/woocommerce/assets/fonts/star.ttf") format("truetype"),
       url("../../../../../../plugins/woocommerce/assets/fonts/star.svg#star") format("svg");
  font-weight: normal;
  font-style: normal;
}



// Review Form
// =============================================================================

.woocommerce,
.woocommerce-page {
  #respond {
    margin-bottom: 0;
  }

  #reply-title {
    font-size: 200%;
  }

  #comments {
    position: relative;
    margin-top: 0;

    article.comment {
      margin