ions
// =============================================================================

//
// Formus and topics.
//

.bbp-forum-info,
.bbp-topic-title {
  width: 60%;
  @include break(baby-bear) {
    width: 100%;
  }
}

.bbp-forum-topic-count,
.bbp-topic-voice-count {
  width: 10%;
  padding: 0 5px;
  text-align: center;
  @include break(middle-bear) {
    width: 15%;
  }
  @include break(baby-bear) {
    display: none;
  }
}

.bbp-forum-reply-count,
.bbp-topic-reply-count {
  width: 10%;
  padding: 0 5px;
  text-align: center;
  @include break(middle-bear) {
    display: none;
  }
}

.bbp-forum-freshness,
.bbp-topic-freshness {
  width: 20%;
  padding: 0 5px;
  text-align: center;
  @include break(middle-bear) {
    width: 25%;
  }
  @include break(baby-bear) {
    display: none;
  }
}


//
// Item info.
//

.x-bbp-item-info-header {
  margin: (-$bpSpacingSm) (-$bpSpacingSm) $bpSpacingSm;
  border-bottom: $bpBorder;
  padding: $bpSpacingSm;
  @include font-size(0.9);
  letter-spacing: 1px;
  line-height: 1;
  text-transform: uppercase;
  background-color: $bpAccentColor;
  @include clearfix();

  .x-item-info-date {
    float: left;
  }

  .x-item-info-permalink {
    float: right;
    color: $textColor;

    &:hover {
      color: $accentColor;
    }

    @include break(middle-bear) {
      display: none;
    }
  }

  .bbp-admin-links {
    clear: both;
    float: left;
    width: 100%;
    margin: $bpSpacingSm 0 0;
    text-align: left;

    .x-bbp-admin-links-inner {
      margin: 0 (-$bpSpacingSm - $bpBorderWidth) (-$bpSpacingSm - $bpBorderWidth) (-$bpSpacingSm);
      border-top: $bpBorder;
      @include clearfix();
    }

    a {
      display: block;
      border-right: $bpBorder;
      border-bottom: $bpBorder;
      padding: 10px $bpSpacingSm;
      line-height: 1;
      color: $bpNavSubnavColor;
      background-color: $bpAccentColor;
      @include text-overflow();

      width: 20%;
      float: left;

      @include break(middle-bear) {
        width: 33.3333%;
      }

      @include break(baby-bear) {
        width: 50%;
      }

      &:hover {
        background-color: $baseModBackground;
      }
    }
  }
}

.x-bbp-item-info-content {
  @include clearfix();

  .x-bbp-item-info-author {
    width: 80px;
    float: left;
    line-height: 1;
    text-align: center;
    @include break(baby-bear) {
      width: 50px;
    }

    .bbp-author-avatar {
      display: block;
      margin-bottom: 10px;
    }

    .bbp-author-name,
    .bbp-author-role,
    .bbp-author-ip {
      display: block;
      @include font-size(0.9);
      letter-spacing: 1px;
      text-transform: uppercase;
      @include text-overflow();
    }

    .bbp-author-name {
      color: $headingsColor;

      &:hover {
        color: $accentColor;
      }
    }

    .bbp-author-role,
    .bbp-author-ip {
      margin: 5px 0 0;
    }
  }

  .x-bbp-item-info-the-content {
    width: calc(100% - 95px);
    margin-bottom: 5px;
    float: right;
    line-height: $baseLineHeight;
    @include break(baby-bear) {
      width: calc(100% - 65px);
    }
  }
}



// Form: Search
// =============================================================================

.x-bbp-search-form {
  margin-bottom: $bpSpacingSm;
  border: $bpBorder;
  padding: $bpSpacingSm;
  background-color: $bpAccentColor;
  @include clearfix();

  label {
    margin: 0;
    width: 100%;
  }

  input {
    margin: 0;
    width: 100%;
  }

  input[type="submit"] {
    display: none;
  }
}



// Form: General
// =============================================================================

.x-bbp-general-form {
  &.bbp-topic-tag-form {
    .bbp-form {
      margin-top: $bpSpacingLg;

      &:first-of-type {
        margin-top: 0;
      }
    }
  }

  form {
    margin: 0;
    border: 0;
    padding: 0;
  }

  legend {
    margin: 0;
    border: 0;
    padding: $bpSpacingLg 0 $bpSpacingSm;
    padding: 0 0 $bpSpacingSm;
    font-size: 125%;
    line-height: 1.4;
    color: $headingsColor;
  }

  label {
    + br {
      display: none;
    }
  }

  input[type="radio"] + label,
  input[type="checkbox"] + label {
    display: inline;
  }

  select,
  textarea,
  input[type="text"],
  input[type="password"] {
    margin: 0;
    width: 100%;
  }

  textarea {
    resize: vertical;
  }

  .bbp-the-content-wrapper {
    margin-bottom: $baseMargin;
  }

  .form-allowed-tags {
    display: none;
  }
}

.bbp-submit-wrapper {
  @include clearfix();

  button[type="submit"] {
    float: left;
    min-width: 70px;
    display: block;
    height: 2.65em;
    margin: 0 10px 0 0;
    border: 0;
    padding: 0 0.5em;
    @include font-size(1.3);
    line-height: 1;
    text-shadow: none;
    color: $white;
    background-color: $accentColor;
    @include box-shadow(#{none});

    &:focus,
    &:active {
      outline: 0;
    }
  }
}

.bbp-topics,
.bbp-replies,
.bbp-pagination,
.bbp-template-notice {
  + .x-bbp-general-form {
    margin-top: $bpSpacingLg;
  }
}



// User
// =============================================================================

.x-bbp-user-header {
  margin: 0 0 (-$bpBorderWidth);
  border: $bpBorder;
  padding: $bpSpacingSm;
  background-color: $bpAccentColor;

  #bbp-user-avatar {
    float: left;
    width: 100px;
    text-align: center;
  }

  .x-bbp-user-header-content {
    float: right;
    width: calc(100% - 115px);
  }

  .x-bbp-user-header-title {
    margin: 0;
    font-size: 165%;
    line-height: 1;
    text-transform: uppercase;
  }

  @include break(middle-bear) {
    #bbp-user-avatar {
      display: none;
    }

    .x-bbp-user-header-content {
      width: 100%;
    }
  }
}

#bbp-user-navigation {
  margin: 0 0 $bpSpacingLg;
  border: $bpBorder;
  background-color: $bpAccentColor;

  > ul {
    margin: 0 (-$bpBorderWidth) (-$bpBorderWidth) 0;
    list-style: none;
    @include clearfix();

    > li {
      display: inline-block;
      width: 33.3333%;
      float: left;

      @include break(middle-bear) {
        width: 50%;
      }

      @include break(baby-bear) {
        width: 100%;
      }

      a {
        display: block;
        position: relative;
        border-right: $bpBorder;
        border-bottom: $bpBorder;
        padding: 10px 11px;
        line-height: 1;
        color: $bpNavSubnavColor;
        background-color: $bpAccentColor;
        @include text-overflow();
      }

      a:hover,
      &.current a {
        color: $accentColor;
        background-color: $baseModBackground;
      }
    }
  }
}

#bbp-user-body {
  .entry-title {
    margin: $bpSpacingLg 0 10px;
    @include font-size(2.4);
  }
}



// Typography
// =============================================================================

.bbp-forum-title,
.bbp-topic-permalink {
  display: inline-block;
  margin: -3px 0 10px;
  font-size: 125%;
  line-height: 1.4;
  color: $headingsColor;

  &:hover {
    color: $accentColor;
  }
}

.bbp-forum-content,
.bbp-topic-meta {
  line-height: $baseLineHeight;
}

.bbp-topic-meta {
  @include font-size(0.9);
  letter-spacing: 1px;
  text-transform: uppercase;

  .bbp-topic-started-in {
    display: none;
  }
}

.bbp-body .bbp-forum-freshness,
.bbp-body .bbp-topic-freshness {
  @include font-size(0.9);
  letter-spacing: 1px;
  line-height: $baseLineHeight;
  text-transform: uppercase;

  a {
    display: block;
    color: $textColor;
    @include text-overflow();

    &:hover {
      color: $accentColor;
    }
  }

  .bbp-topic-meta {
    margin: 5px 0 0;
  }
}


//
// Lists and logs.
//

.bbp-forums-list,
.bbp-topic-revision-log,
.bbp-reply-revision-log {
  margin: $bpSpacingSm 0 0;
  padding: 0 0 0;
  list-style: none;

  > li {
    margin: 0 0 (-$bpBorderWidth);
    border: $bpBorder;
    padding: 10px;
    @include font-size(0.9);
    letter-spacing: 1px;
    line-height: $baseLineHeight;
    text-transform: uppercase;
    background-color: $bpAccentColor;
  }
}


//
// Context title.
//

.x-context-title {
  display: block;
  margin: 0 0 10px;
  @include font-size(0.9);
  font-weight: $baseFontWeight;
  letter-spacing: 1px;
  line-height: 1;
  @include text-overflow();
}



// Pagination
// =============================================================================

.bbp-pagination {
  margin: $bpSpacingSm 0 0;
}

.bbp-pagination-links {
  display: inline-block;
  @include clearfix();
}



// Notification Counts
// =============================================================================

.x-bbp-count {
  display: inline-block;
  padding: 3px 4px;
  line-height: 1;
  text-align: center;
  @include font-size(0.9);
  color: $white;
  background-color: $accentColor;
  z-index: 5;
}

.bbp-forum-topic-count,
.bbp-forum-reply-count,
.bbp-topic-voice-count,
.bbp-topic-reply-count {
  .x-bbp-count {
    display: block;
    margin: 2px auto 0;
    max-width: 35px;
    @include text-overflow();
  }
}



// Stand Alone Buttons
// =============================================================================

.x-btn-bbp,
.bbp-topic-tags a,
.bbp-row-actions a,
.x-bbp-header .actions a,
.quicktags-toolbar input.button {
  display: inline-block;
  margin: 0;
  border: 0;
  padding: 5px 6px;
  @include font-size(1.1);
  letter-spacing: 0;
  line-height: 1;
  text-align: center;
  text-shadow: none;
  text-transform: none;
  color: $white;
  background-color: $accentColor;
  @include box-shadow(#{none});

  &:hover {
    color: $white;
  }
}


//
// Topic tags.
//

.bbp-topic-tags {
  margin: $bpSpacingLg 0 0;
  border: $bpBorder;
  padding: 10px;
  line-height: 1;
  text-align: center;
  background-color: $bpAccentColor;

  span {
    display: block;
    margin: 0 0 4px;
    @include font-size(0.9);
    letter-spacing: 1px;
    line-height: 1;
    text-transform: uppercase;
  }

  a {
    margin: 4px 2px 0;
  }
}


//
// Row actions.
//

.bbp-row-actions {
  display: block;
  margin-bottom: 5px;

  a {
    margin-right: 3px;
    padding: 2px 5px;
    line-height: 1.1;
  }
}


//
// Quicktags toolbar.
//

.quicktags-toolbar {
  input.button {
    margin: 0 5px 5px 0;

    &:focus,
    &:active {
      outline: 0;
    }
  }
}



// Status
// =============================================================================

.bbp-body {
  > div.hentry.status-spam,
  > div.hentry.status-trash {
    .x-item-info-date,
    .x-item-info-permalink {
      color: $errorText;
    }
  }

  > div.hentry.status-spam {
    .bbp-reply-spam-link {
      color: $errorText;
    }
  }

  > div.hentry.status-trash {
    .bbp-reply-delete-link,
    .bbp-topic-delete-link {
      color: $errorText;
    }
  }
}



// Navigation
// =============================================================================

.x-navbar .x-nav > .x-menu-item-bbpress {
  > a {
    > span:after {
      display: none;
    }
  }

  > .sub-menu {
    > li {
      > a {
        > i {
          margin-right: 2px;
        }
      }
    }
  }
}



// BuddyPress Integration
// =============================================================================

.buddypress {
  #item-body > #bbpress-forums {
    margin-top: $bpSpacingLg;
  }

  #bbpress-forums h3,
  .x-item-list-tabs-subnav + h3 {
    display: none;
  }

  #bbpress-forums .entry-title {
    margin: $bpSpacingLg 0 10px;
    @include font-size(2.4);
    line-height: 1.3;

    &:first-of-type {
      margin-top: 0;
    }
  }
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                // =============================================================================
// SCSS/SITE/STACKS/ETHOS/_WOOCOMMERCE.SCSS
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
    margin: 0 4% 4.5% 0;
    padding: 0;
    text-align: center;
    background-color: $baseModBackground;

    &.first {
      clear: both;
    }

    &.last {
      margin-right: 0;
    }

    .onsale {
      top: -45px;
      left: -95px;
    }

    .entry-product {
      position: relative;
    }

    .entry-featured {
      overflow: hidden;
      z-index: 0;

      a {
        display: block;
      }

      img {
        min-width: 100%;
      }
    }

    .entry-wrap {
      position: absolute;
      top: calc(100% - 4em);
      left: 0;
      right: 0;
      bottom: auto;
      padding: 0;
      @include font-size(1.4);
      @include transition(#{all 0.615s $easeOutExpo});

      &:before {
        content: "";
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 50em;
        background-color: $entryCoverBackground;
        z-index: 0;
        @include transition(#{all 0.615s $easeOutExpo});
      }
    }

    .entry-header {
      position: relative;

      h3 {
        margin: 0;
        padding: 1.5em;
        @include font-size(1.4);
        line-height: 1;
        text-transform: uppercase;
        @include text-overflow();

        a {
          color: $white;
        }
      }

      .price {
        > del {
          display: none;
        }

        > .amount,
        > ins > .amount {
          @include font-size(2.4);
          color: $white;
        }
      }

      .button {
        margin: 15px;
        padding: 0.45em 0.5em 0.575em;
        display: block;
        @include font-size(1.4);
      }
    }

    .added_to_cart {
      display: none;
    }

    &:hover {
      .entry-wrap {
        top: calc(100% - 10.385em);

        &:before {
          background-color: $entryCoverBackgroundHover;
        }
      }

      .star-rating-container {
        @include opacity(1);
      }
    }
  }
}



// Product Styles
// =============================================================================

.woocommerce,
.woocommerce-page {
  .entry-wrap > .entry-content:first-child {
    margin: 0;
    @include clearfix();
  }

  div.product {
    .images {
      overflow: hidden;
      position: relative;
      float: left;
      width: 48%;
      @include break(middle-bear) {
        width: 100%;
      }

      .onsale {
        top: -45px;
        left: -95px;
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
        font-size: 228%;
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
    margin: 50px 0 -4%;

    h2 {
      margin: 0 0 0.5em;
      font-size: 200%;
    }

    ul.products {
      li.product {
        border: 1px solid $shortcodeBorderColor;

        .entry-featured {
          border-bottom: 1px solid $shortcodeBorderColor;
        }
      }
    }
  }
}

.page,
.single-post {
  .x-main {
    .woocommerce {
      ul.products {
        li.product {
          .entry-featured {
            margin: 0;
          }
        }
      }
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
      width: 3.65em;
      margin: 0;
      padding-left: 0.5em;
      padding-right: 0.5em;
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
        border: 1px solid $tableBorder;
        padding: 15px;
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
    font-size: 164%;
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
// Sidebar widgets.
//

.x-sidebar {
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
        margin: 0;
        padding-top: 8px;
        padding-bottom: 8px;

        &:first-child {
          margin-top: 0;
        }

        a {
          padding: 0;
        }
      }
    }
  }
}


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
      margin-bottom: 5px;
      font-size: 81.25%;
      @include clearfix();

      &:first-child {
        margin-top: 20px;
      }

      &:last-child {
        margin-bottom: 0;
      }

      a {
        display: block;
        margin-bottom: 2px;
        border-bottom: 0;
        font-size: 135.7%;
        line-height: 1.7;

        img {
          @include img_thumbnail();
          padding: 3px !important;
          float: left;
          width: 65px;
          margin-right: 0.65em;
          @include box-shadow(#{none !important});
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
    font-size: 100%;
    text-align: center;
  }

  ul {
    li {
      position: relative;

      .remove {
        display: block;
        position: absolute;
        right: 0;
        bottom: 4px;
        margin: 0;
        width: 18px;
        height: 18px;
        @include font-size(1.8);
        line-height: 18px;
        text-align: center;
        text-decoration: none;
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
    margin-bottom: 0;
    border: 1px solid $shortcodeBorderColor;
    border-bottom: 0;
    padding: 7px;
    @include font-size(1.1);
    line-height: 1.1;
    text-align: center;
    text-transform: uppercase;
  }

  .buttons {
    border: 1px solid $shortcodeBorderColor;
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
      text-decoration: none;
      color: $navbarBackground;
      background-color: transparent;
      @include text-overflow();
      @include box-shadow(#{none});

      &:hover {
        background-color: $shortcodeBorderColor;
      }

      &:first-child {
        border-right: 1px solid $shortcodeBorderColor;
      }
    }
  }
}


//
// Layered nav and layered nav filters.
//

.widget_layered_nav,
.widget_layered_nav_filters {
  .chosen {
    .count {
      background-color: $baseModBackground;
    }
  }
}

.widget_layered_nav {
  li {
    position: relative;

    .count {
      position: absolute;
      top: 50%;
      right: 10px;
      width: 24px;
      height: 24px;
      margin-top: -12px;
      line-height: 24px;
      text-align: center;
      background-color: $shortcodeBorderColor;
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
    background-color: $shortcodeBorderColor;

    .ui-slider-handle {
      position: absolute;
      top: 50%;
      width: 21px;
      height: 21px;
      margin-top: -11px;
      cursor: pointer;
      background-color: $baseModBackground;
      border-radius: 100em;
      outline: none;
      @include box-shadow(#{0 1px 2px rgba(0, 0, 0, 0.35)});
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
      background-color: $shortcodeBorderColor;
      z-index: 1;
    }
  }

  .price_slider_amount {
    @include clearfix();

    .button,
    .price_label {
      float: left;
      width: 50%;
      margin: 0;
      border: 1px solid $shortcodeBorderColor;
      padding: 7px;
      @include font-size(1.1);
      line-height: 1.1;
      text-align: center;
      text-shadow: none;
      background-color: transparent;
      @include text-overflow();
    }

    .button {
      color: $navbarBackground;
      @include box-shadow(#{none});

      &:hover {
        background-color: $shortcodeBorderColor;
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
    margin-bottom: 2px;
  }
}



// Columns
// ==================================