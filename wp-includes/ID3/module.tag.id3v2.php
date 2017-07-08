
    height: 6em;
  }

  .editfield {
    margin-bottom: $baseMargin;

    select,
    textarea,
    input[type="text"] {
      margin: 2px 0 5px;
    }

    p:last-child {
      margin-bottom: 0;
    }

    .description {
      margin: 0;
    }
  }

  .field-visibility-settings {
    display: none;
  }

  .field-visibility-settings-toggle {
    margin-bottom: 0;
  }

  .description {
    opacity: 0.65;
  }

  div.radio,
  div.checkbox {
    margin-bottom: $baseMargin;
  }

  div.radio {
    label {
      ul {
        margin-bottom: 10px;
      }

      &:last-child {
        ul {
          margin-bottom: 0;
        }
      }
    }
  }

  ul.radio {
    margin: 0;
  }
}



// Form: What's New
// =============================================================================

.buddypress.activity.directory #whats-new-form {
  margin-bottom: -$bpBorderWidth;
}

.buddypress #whats-new-form {
  margin-bottom: $bpSpacingSm;
  border: $bpBorder;
  padding: $bpSpacingSm;
  background-color: $bpAccentColor;

  .activity-greeting {
    margin-bottom: $bpSpacingSm;
    font-size: 1.25em;
    line-height: 1;
  }

  textarea {
    margin: 0 0 2px;
    width: 100%;
    height: 6em;
  }

  #whats-new-options {
    display: none;
  }

  #x-whats-new-options-inner {
    padding: $bpSpacingSm 0 2px;
  }


  //
  // Submit button.
  //

  #whats-new-submit {
    float: left;
    width: 70px;
  }

  #aw-whats-new-submit {
    display: block;
    width: 100%;
    height: 2.65em;
    margin: 0;
    border: 0;
    padding: 0 0.65em;
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


  //
  // Submit select.
  //

  #whats-new-post-in-box {
    float: right;
    width: calc(100% - 85px);
  }

  #whats-new-post-in {
    width: 100%;
    margin: 0;

    &:focus,
    &:active {
      outline: 0;
    }
  }


  //
  // Form active state.
  //

  &.active {
    #whats-new-options {
      display: block;
    }
  }
}



// Form: Search Groups, Members, Messages, and Blogs
// =============================================================================

.buddypress {
  #search-groups-form,
  #search-members-form,
  #search-message-form,
  #blogs-directory-form {
    margin-bottom: -$bpBorderWidth;
    border: $bpBorder;
    padding: $bpSpacingSm;
    background-color: $bpAccentColor;
    @include clearfix();

    input[type="submit"] {
      display: none;
    }

    label {
      margin: 0;
      width: 100%;

      input {
        margin: 0;
        width: 100%;
      }
    }
  }
}



// Item Header
// =============================================================================

.buddypress {
  #item-header {
    margin: 0 0 (-$bpBorderWidth);
    border: $bpBorder;
    padding: $bpSpacingSm;
    background-color: $bpAccentColor;

    #message {
      float: left;
      width: 100%;
      margin: $bpSpacingSm 0 0;
    }
  }

  #item-header-avatar {
    float: left;
    width: 100px;
    text-align: center;

    > a {
      display: block;
      position: relative;

      > .highlight {
        display: block;
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        padding: 6px;
        @include font-size(0.9);
        letter-spacing: 1px;
        line-height: 1;
        text-transform: uppercase;
        color: $white;
        background-color: rgba(0, 0, 0, 0.35);
      }
    }

    .activity {
      display: block;
      margin: $bpSpacingSm 0 0;
      @include font-size(0.9);
      letter-spacing: 1px;
      line-height: 1.3;
      text-transform: uppercase;
    }
  }

  #item-header-content {
    float: right;
    width: calc(100% - 115px);
  }

  .x-item-header-title {
    margin: 0 0 10px;
    font-size: 165%;
    line-height: 1;
    text-transform: uppercase;
  }

  .user-nicename {
    margin: 0;
    font-size: 165%;
    line-height: 1.2;
    @include text-overflow();
  }

  #item-meta {
    p:last-of-type {
      margin-bottom: -2px;
    }
  }

  #latest-update {
    margin: 10px 0 -2px;
  }

  .item-action {
    margin: $bpSpacingSm 0 0;
    border-top: $bpBorder;

    > h3 {
      margin: 5px 0;
      float: left;
      @include font-size(0.9);
      letter-spacing: 1px;
      line-height: 1;
      text-transform: uppercase;
    }

    > ul {
      clear: both;
      margin: 0;
      list-style: none;
      @include clearfix();

      li {
        display: inline-block;
        width: 25px;
      }
    }
  }

  @include break(middle-bear) {
    #item-header-avatar,
    #latest-update {
      display: none;
    }

    #item-header-content {
      width: 100%;
    }
  }
}



// Navigation
// =============================================================================

.buddypress {
  .item-list-tabs {
    > ul {
      list-style: none;

      > li {
        display: inline-block;

        > a,
        > span {
          display: block;
          line-height: 1;
        }
      }
    }
  }

  .x-item-list-tabs-nav {
    margin: 0 0 $bpSpacingSm;
    border: $bpBorder;
    background-color: $bpAccentColor;

    > ul {
      margin: 0 (-$bpBorderWidth) (-$bpBorderWidth) 0;
      @include clearfix();

      > li {
        width: 33.3333%;
        float: left;

        @include break(middle-bear) {
          width: 50%;
        }

        @include break(baby-bear) {
          float: none;
          width: 100%;
        }

        > a,
        > span {
          position: relative;
          border-right: $bpBorder;
          border-bottom: $bpBorder;
          padding: 10px 11px;
          color: $bpNavSubnavColor;
          background-color: $bpAccentColor;
        }

        > a:hover,
        &.current > a,
        &.selected > a, {
          background-color: $baseModBackground;
        }
      }
    }
  }

  .x-item-list-tabs-subnav {
    margin: 0 0 $bpSpacingSm;
    text-align: center;

    > ul {
      margin: 0;

      > li {
        > a {
          margin: 0 10px;
          color: $bpNavSubnavColor;
        }

        > a:hover,
        &.current > a {
          color: $accentColor;
        }

        &.groups-members-search {
          margin-top: (-$bpSpacingSm - $bpBorderWidth);
          width: 100%;
        }

        &.last {
          display: block;
          margin-top: $bpSpacingLg;

          label {
            display: none;
          }

          &.member-message-search {
            label {
              display: block;
            }
          }

          select {
            margin: 0;
            width: 100%;

            &:focus,
            &:active {
              outline: 0;
            }
          }
        }
      }
    }

    + #message,
    + .messages #message {
      margin: $bpSpacingLg 0 0;
    }
  }
}



// List: All Items
// =============================================================================

.buddypress .item-list {
  margin: 0;
  list-style: none;

  &#friend-list {
    margin-top: $bpSpacingLg;
  }

  > li {
    margin: 0 0 $bpSpacingSm;
    border: $bpBorder;
    padding: $bpSpacingSm;
    background-color: $baseModBackground;
    @include clearfix();

    &:last-child {
      margin-bottom: 0;
    }
  }
}



// List: Activity
// =============================================================================

.buddypress .activity {
  #activity-loop-form {
    margin: 0;
  }
}

.buddypress .activity-list {

  //
  // Top level activity item.
  //

  > li {
    &.load-newest,
    &.load-more {
      padding: 0;

      > a {
        display: block;
        padding: $bpSpacingSm;
        line-height: 1;
        text-align: center;
      }
    }
  }

  .activity-content {
    clear: both;

    .activity-inner {
      margin-bottom: $bpSpacingSm;
    }
  }


  //
  // Replies to activity item.
  //

  .x-activity-comments-outer {
    clear: both;
    float: left;
    width: 100%;
    margin-bottom: (-$bpSpacingSm);
  }

  .activity-comments {
    margin: 15px (-$bpSpacingSm) 0;

    .ac-form {
      clear: both;
      margin: 0;
      border-top: $bpBorder;
      padding: $bpSpacingSm;
      background-color: $bpAccentColor;

      textarea {
        margin-bottom: $bpSpacingSm - 1px;
        width: 100%;
        height: 6em;
      }

      a,
      input[type="submit"] {
        display: block;
        float: right;
        margin: 0;
        border: 0;
        padding: 7px 12px;
        @include font-size(1.2);
        line-height: 1;
        text-shadow: none;
        color: $textColor;
        background-color: transparent;
        @include box-shadow(#{none});

        &:focus,
        &:active {
          outline: 0;
        }
      }

      input[type="submit"] {
        color: $white;
        background-color: $accentColor;
      }
    }

    .ac-reply-avatar {
      float: left;
    }

    .ac-reply-content {
      float: right;
      width: calc(100% - #{$bpCalcSpacingLg});
    }

    > ul {
      float: right;
      width: 100%;
      margin: 0;
      background-color: $bpAccentColor;
      list-style: none;

      li {
        .x-acomment-content-wrap {
          margin: 0;
          border-top: $bpBorder;
          padding: $bpSpacingSm;
          @include font-size(1.2);
        }

        ul {
          margin: 0;
          list-style: none;

          li {
            margin: 0;
          }
        }
      }

      .acomment-avatar {
        float: left;
        width: 25px;
      }

      .acomment-meta,
      .acomment-content,
      .acomment-options {
        float: right;
        width: calc(100% - #{$bpCalcSpacingSm});
      }

      .acomment-meta {
        @include text-overflow();
      }

      .acomment-content {
        margin: 3px 0;
      }

      .acomment-options {
        > a {
          &:after {
            content: "\0020\2219\0020";
            color: $textColor;
            opacity: 0.65;
          }

          &:last-child:after {
            content: "";
          }
        }
      }
    }
  }
}



// List: Groups
// =============================================================================

.buddypress #groups-directory-form {
  margin: 0;
}

.buddypress {
  #group-list,
  #groups-list {
    &.invites {
      margin-top: $bpSpacingLg;
    }

    > li {
      .item-content {
        clear: both;

        .item-desc {
          margin-bottom: $bpSpacingSm;
        }
      }
    }
  }
}



// List: Blogs
// =============================================================================

.buddypress #blogs-list {
  .meta {
    max-width: 200px;
    @include text-overflow();
    @include break(middle-bear) {
      max-width: none;
    }
  }
}



// List Item: Header
// =============================================================================

.buddypress .x-list-item-header {
  margin-bottom: $bpSpacingSm;
  @include font-size(1.2);
  @include clearfix();

  .x-list-item-avatar-wrap {
    float: left;
    width: 45px;
  }

  .x-list-item-header-info {
    float: right;
    width: calc(100% - #{$bpCalcSpacingLg});
  }

  p {
    margin: 5px 0 0;
    line-height: 1.5;
    @include text-overflow();

    img {
      display: none;
    }
  }

  .activity,
  .time-since {
    display: block;
    color: $textColor;
    opacity: 0.5;
    @include text-overflow();
    @include transition(#{opacity 0.3s ease});
  }

  .time-since:hover {
    opacity: 0.75;
  }
}



// List Item: Meta
// =============================================================================

.buddypress .x-list-item-meta {
  clear: both;
  float: left;
  width: 100%;
  margin-bottom: (-$bpSpacingSm);
}

.buddypress .x-list-item-meta-inner {
  margin: 0 (-$bpSpacingSm);
  border-top: $bpBorder;
  @include clearfix();

  > a,
  > .generic-button {
    display: block;
    float: left;
    margin: 0;
  }

  > a,
  > .generic-button > a,
  > .meta {
    padding: 9px $bpSpacingSm;
    @include font-size(1.1);
    line-height: 1.2;
    text-align: center;
    color: $textColor;
  }

  > a,
  > .generic-button > a {
    display: block;
    float: left;
    position: relative;
    border-right: $bpBorder;
  }

  > .meta {
    float: right;
    border-left: $bpBorder;
    background-color: $bpAccentColor;

    > a {
      color: $textColor;
    }
  }

  @include break(middle-bear) {
    > a,
    > .generic-button {
      float: none;
      width: 100%;

      > a {
        float: none;
        width: 100%;
      }
    }

    > a,
    > .generic-button > a {
      border-right: 0;
      border-bottom: $bpBorder;
    }

    > a:last-child,
    > .generic-button:last-child > a {
      border-bottom: 0;
    }

    > .meta {
      float: none;
      width: 100%;
      border-left: 0;
      border-bottom: $bpBorder;

      &:last-child {
        border-bottom: 0;
      }
    }
  }
}



// BP Widget
// =============================================================================

.buddypress .bp-widget {
  h4 {
    margin-bottom: 10px;
    @include font-size(2.4);
    line-height: 1.3;
  }
}



// Button Navigation
// =============================================================================

.buddypress {

  //
  // All button navigation containers.
  //

  .button-nav,
  #item-buttons {
    @include clearfix();

    a {
      float: left;
      display: block;
      margin: 5px 5px 0 0;
      padding: 5px 6px;
      @include font-size(1.1);
      line-height: 1;
      text-align: center;
      color: $white;
      background-color: $accentColor;
    }
  }


  //
  // Button navigation list.
  //

  .button-nav {
    margin: 0 0 $baseMargin;
    list-style: none;
  }

  h4 + .button-nav {
    margin-top: -5px;
  }


  //
  // Item header item buttons.
  //

  #item-buttons {
    margin-top: 9px;
  }
}



// Stand Alone Buttons
// =============================================================================

.buddypress .x-btn-bp {
  display: inline-block;
  margin: 0;
  padding: 5px 6px;
  @include font-size(1.1);
  line-height: 1;
  text-align: center;
  color: $white;
  background-color: $accentColor;
}



// Pagination
// =============================================================================

.buddypress .pagination {
  margin-top: $bpSpacingSm;

  .pagination-links {
    display: inline-block;
    @include clearfix();
  }
}



// Tables
// =============================================================================

.buddypress .entry-content {
  table {
    border: $bpBorder;
    background-color: $baseModBackground;

    th,
    td {
      border: $bpBorder;
    }
  }
}



// Admin Bar
// =============================================================================

#wpadminbar .quicklinks li#wp-admin-bar-user-admin-with-avatar > a img,
#wpadminbar .quicklinks li#wp-admin-bar-group-admin-with-avatar > a img {
  display: inline;
  float: none;
  width: 16px;
  height: 16px;
  margin: -2px 10px 0 -5px;
  border: 1px solid #999;
  padding: 0;
  background: #eee;
  vertical-align: middle;
}

#wpadminbar .quicklinks li#wp-admin-bar-group-admin-with-avatar ul {
  left: 0;
}

#wpadminbar .quicklinks li#wp-admin-bar-group-admin-with-avatar ul ul {
  left: 0;
}

#wpadminbar .quicklinks li#wp-admin-bar-my-account a span.count,
#wpadminbar .quicklinks li#wp-admin-bar-my-account-with-avatar a span.count,
#wpadminbar .quicklinks li#wp-admin-bar-bp-notifications #ab-pending-notifications {
  display: inline;
  padding: 2px 5px;
  color: $white;
  background: #21759b;
  font-size: 10px;
  font-weight: bold;
  text-shadow: none;
  border-radius: 10px;
}

#wpadminbar .quicklinks li#wp-admin-bar-bp-notifications #ab-pending-notifications {
  background: #ddd;
  color: #333;
  margin: 0
}

#wpadminbar .quicklinks li#wp-admin-bar-bp-notifications #ab-pending-notifications.alert {
  color: $white;
  background-color: #1fb3dd;
}

#wpadminbar .quicklinks li#wp-admin-bar-bp-notifications > a {
  padding: 0 0.5em;
}

#wp-admin-bar-user-info img.avatar{
  width: 64px;
  height: 64px;
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      // =============================================================================
// SCSS/SITE/STACKS/RENEW/_BBPRESS.SCSS
// -----------------------------------------------------------------------------
// Contains styles for bbPress.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Base Styles
//   02. Single Loop Item Sections
//   03. Form: Search
//   04. Form: General
//   05. User
//   06. Typography
//   07. Pagination
//   08. Notification Counts
//   09. Stand Alone Buttons
//   10. Status
//   11. Navigation
//   12. BuddyPress Integration
// =============================================================================

// Base Styles
// =============================================================================

.bbpress .entry-content {
  margin-top: 0;
}

.bbp-forums,
.bbp-topics,
.bbp-replies,
.bbp-search-results,
.bbp-header > ul,
.bbp-body > ul,
.bbp-footer > ul {
  margin: 0;
  list-style: none;
}

.bbp-forums,
.bbp-topics {
  @include box-shadow(#{none});
}

.bbp-forums {
  + .bbp-topics {
    margin-top: $bpSpacingLg;
  }
}

.bbp-topics {
  + .bbp-no-topic,
  + .bbp-template-notice {
    margin-top: $bpSpacingLg;
  }
}

.bbp-header,
.bbp-footer {
  margin: 0 0 (-$bpBorderWidth);
  border: $bpBorder;
  background-color: $bpAccentColor;
}

.bbp-header,
.bbp-header > ul,
.bbp-body,
.bbp-body > ul,
.bbp-body > .hentry,
.bbp-footer,
.bbp-footer > ul {
  @include clearfix();
}

.bbp-header > ul > li,
.bbp-body > ul > li,
.bbp-footer > ul > li {
  float: left;
  line-height: 1;
}

.bbp-header,
.bbp-footer {
  padding: 10px $bpSpacingSm;
  @include font-size(0.9);
  letter-spacing: 1px;
  text-transform: uppercase;

  > ul > li,
  .bbp-reply-author,
  .bbp-reply-content {
    @include text-overflow();
  }
}

.bbp-body {
  > ul,
  > div.hentry {
    margin: 0 0 (-$bpBorderWidth);
    border: $bpBorder;
    padding: $bpSpacingSm;
    background-color: $baseModBackground;

    &:last-child {
      margin-bottom: (-$bpBorderWidth);
    }
  }

  > div.hentry {
    margin-bottom: $bpSpacingSm;
  }
}

.bbpress.reply #bbpress-forums {
  > div.reply {
    margin: 0;
    border: $bpBorder;
    padding: $bpSpacingSm;
    background-color: $baseModBackground;
  }
}

.x-bbp-header {
  margin: 0 0 $bpSpacingSm;
  @include clearfix();

  .actions {
    line-height: 1;
  }
}



// Single Loop Item Sections
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
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            // =============================================================================
// SCSS/SITE/STACKS/INTEGRITY/INC/_VARIABLES-LIGHT.SCSS
// -----------------------------------------------------------------------------
// SASS variables used specifically for Integrity.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Appearance
//   02. Scaffolding
//   03. Links
//   04. Typography
//       a. Font Families
//       b. Base Styles
//       c. Heading Styles
//   05. Tables
//   06. Buttons
//       a. Base Button Styling
//       b. Box Shadows
//   07. Forms and Alerts
//       a. Base Form Styling
//       b. Inputs
//       c. Box Shadows
//       d. Fine-Tune Element Alignment
//       e. Form States and Alert Colors
//       f. Alerts
//   08. Component Variables
//       a. Input Placeholder Text
//       b. Navbar
//       c. Dropdowns
//       d. Alerts
//       e. Tooltips and Popovers
//       f. Colophon
//   09. BuddyPress
//   10. Grid
//       a. Grid Columns
//       b. Fixed
//       c. Fluid
// =============================================================================

// Appearance
// =============================================================================

$skin:                                        'light'                                             !default;

$baseBoxShadow:                               0 0.15em 0.35em 0 rgba(0, 0, 0, 0.135)              !default;
$baseShortcodeBoxShadow:                      0 0.125em 0.275em 0 rgba(0, 0, 0, 0.075)            !default;
$baseBoxShadowNavbar:                         0 0.15em 0.35em 0 rgba(0, 0, 0, 0.135)              !default;
$baseBoxShadowAccent:                         rgba(255, 255, 255, 0.85)                           !default;
$baseModBackground:                           $white                                              !default;
$siteBackground:                              $baseModBackground                                  !default;
$siteBorderColor:                             rgba(0, 0, 0, 0.275)                                !default;
$siteBoxShadow:                               0 0 5px rgba(0, 0, 0, 0.125)                        !default;
$accentColor:                                 #ff2a13                                             !default;
$entryFeaturedBoxShadow:                      4px 2px 4px -4px rgba(0, 0, 0, 0.135),
                                              -4px 2px 4px -4px rgba(0, 0, 0, 0.135)              !default;
$widgetBorderColor:                           #ddd                                                !default;
$widgetBorderColorRgba:                       rgba(0, 0, 0, 0.1)                                  !default;
$widgetLiABlockBorderColorRgba:               rgba(0, 0, 0, 0.1075)                               !default;
$widgetBlockLinkBorder:                       1px solid $widgetBorderColor                        !default;
$widgetUlBackground:                          transparent                                         !default;
$widgetUlBoxShadow:                           0 1px 1px rgba(255, 255, 255, 0.95)                 !default;
$widgetUlChildrenBoxShadow:                   inset 0 1px 1px rgba(255, 255, 255, 0.95)           !default;
$widgetLiBoxShadow:                           0 1px 1px rgba(255, 255, 255, 0.95)                 !default;
$widgetLiABlockBoxShadow:                     0 1px 1px rgba(255, 255, 255, 0.95)                 !default;
$widgetLiBackgroundHover:                     $baseModBackground                                  !default;
$widgetLiABlockFirstChildBorderTop:           #ddd                                                !default;
$widgetTextShadow:                            0 1px 0 rgba(255, 255, 255, 0.95)                   !default;
$widgetbarBackground:                         $white                                              !default;
$widgetbarColor:                              #727272                                             !default;
$widgetNavMenuCurrentItemBackground:          $baseModBackground                                  !default;
$widgetLiABlockBackgroundHover:               $baseModBackground                                  !default;
$widgetWooButtonStyledBoxShadow:              $widgetUlBoxShadow                                  !default;
$widgetWooButtonStyledBackgroundHover:        rgba(255, 255, 255, 0.35)                           !default;
$widgetWooLayeredNavChosenBackground:         $widgetLiBackgroundHover                            !default;
$widgetWooLayeredNavCountBackground:          $widgetLiBackgroundHover                            !default;
$widgetWooLayeredNavCountBoxShadow:           inset 0 1px 2px rgba(0, 0, 0, 0.25)                 !default;
$widgetWooPriceFilterSliderBoxShadow:         inset 0 1px 2px 0 rgba(0, 0, 0, 0.15),
                                              0 1px 0 0 rgba(255, 255, 255, 0.85)                 !default;
$widgetWooPriceFilterHandleBackground:        $baseModBackground                                  !default;
$widgetWooPriceFilterRangeBoxShadow:          inset 0 1px 2px 0 rgba(0, 0, 0, 0.45)               !default;
$widgetWooPriceFilterHandleBoxShadow:         0 1px 3px rgba(0, 0, 0, 0.25)                       !default;
$contentDockBoxShadow:                        0 0.085em 0.5em 0 rgba(0, 0, 0, 0.165)              !default;
$contentDockCloseColor:                       darken($baseModBackground, 15%)                     !default;
$tagColor:                                    #c5c5c5                                             !default;
$tagColorRgba:                                rgba(0, 0, 0, 0.375)                                !default;
$tagColorHover:                               #777                                                !default;
$tagColorHoverRgba:                           rgba(0, 0, 0, 0.75)                                 !default;
$tagColorActive:                              $tagColorHover                                      !default;
$tagColorActiveRgba:                          $tagColorHoverRgba                                  !default;
$tagBorderColor:                              #ddd                                                !default;
$tagBorderColorRgba:                          rgba(0, 0, 0, 0.125)                                !default;
$tagBorderColorHover:                         #cfcfcf                                             !default;
$tagBorderColorHoverRgba:                     rgba(0, 0, 0, 0.25)                                 !default;
$tagBorderColorActive:                        #bbb                                                !default;
$tagBorderColorActiveRgba:                    rgba(0, 0, 0, 0.25)                                 !default;
$tagBackgroundColorHover:                     $baseModBackground                                  !default;
$tagBackgroundColorHoverRgba:                 rgba(255, 255, 255, 1)                              !default;
$tagBackgroundColorActive:                    #ebebeb                                             !default;
$tagBackgroundColorActiveRgba:                rgba(0, 0, 0, 0.075)                                !default;
$tagBoxShadow:                                0 1px 1px rgba(255, 255, 255, 0.95)                 !default;
$tagBoxShadowActive:                          inset 0 1px 2px rgba(0, 0, 0, 0.225), $tagBoxShadow !default;
$tagTextShadow:                               0 1px 0 rgba(255, 255, 255, 0.95)                   !default;
$boxedEntryBorder:                            #ddd                                                !default;
$headerLandmarkBackgroundColor:               #e1e1e1                                             !default;
$headerLandmarkBackgroundColorRgba:           rgba(0, 0, 0, 0.1)                                  !default;
$headerLandmarkBoxShadow:                     rgba(255, 255, 255, 0.795)                          !default;
$headerLandmarkSubColor:                      #b7b7b7                                             !default;
$breadcrumbBackground:                        rgba(0, 0, 0, 0.0225)                               !default;
$breadcrumbBorderBottom:                      rgba(0, 0, 0, 0.1)                                  !default;
$breadcrumbTextShadow:                        0 1px 0 rgba(255, 255, 255, 0.55)                   !default;
$breadcrumbBoxShadow:                         rgba(255, 255, 255, 0.715)                          !default;
$commentAvatarBoxShadow:                      inset 0 1px 3px rgba(0, 0, 0, 0.45),
                                              0 1px 0 0 rgba(255, 255, 255, 0.85)                 !default;
$commentNumberBoxShadow:                      inset 0 2px 3px rgba(0, 0, 0, 0.3),
                                              0 2px 1px rgba(255, 255, 255, 1)                    !default;
$commentByPostAuthorTextShadow:               0 1px 0 rgba(255, 255, 255, 1)                      !default;
$colophonBoxShadow:                           inset 0 1px 0 0 rgba(255, 255, 255, 0.8)            !default;
$mejsButtonBorderColor:                       #272727                                             !default;
$mejsGradientTop:                             lighten(#272727, 20%)                               !default;
$mejsGradientBottom:                          #272727                                             !default;
$searchFormIconColor:                         #272727                                             !default;
$paginationItemBoxShadow:                     inset 0 0 0 rgba(0, 0, 0, 0),
                                              0 0.1em 0.45em 0 rgba(0, 0, 0, 0.25)                !default;
$paginationCurrentItemBoxShadow:              inset 0 0.1em 0.35em rgba(0, 0, 0, 0.65),
                                              0 1px 0 0 rgba(255, 255, 255, 0.95)                 !default;
$paginationItemTextShadow:                    0 1px 1px rgba(255, 255, 255, 0.85)                 !default;
$dropdownCollapseLinkColorHover:              #272727                                             !default;
$dropdownCollapseLinkBackgroundHover:         #f5f5f5                                             !default;
$thumbnailBoxShadow:                          0 1px 3px rgba(0, 0, 0, 0.1)                        !default;
$thumbnailBackground:                         $baseModBackground                                  !default;
$shortcodeBoxShadowOuter:                     0 0.125em 0.275em 0 rgba(0, 0, 0, 0.125)            !default;
$shortcodeBoxShadowInner:                     none                                                !default;
$shortcodeBorderColor:                        #ddd                                                !default;
$shortcodeBorderColorRgba:                    rgba(0, 0, 0, 0.15)                                 !default;
$shortcodeAccordionContentBackground:         $baseModBackground                                  !default;
$shortcodeAccordionBeforeColor:               darken($baseModBackground, 20%)                     !default;
$shortcodeTabsContentBackground:              $baseModBackground                                  !default;
$shortcodeTabsContentBoxShadow:               0 0.125em 0.275em 0 rgba(0, 0, 0, 0.125)            !default;
$shortcodeTabNavLinkColor:                    darken($baseModBackground, 25%)                     !default;
$shortcodeTabNavLinkColorHover:               darken($baseModBackground, 50%)                     !default;
$shortcodeTabNavLinkBackground:               darken($baseModBackground, 3%)                      !default;
$shortcodeTabNavLinkBoxShadow:                inset 0 1px 0 0 rgba(255, 255, 255, 0.85)           !default;
$shortcodeSkillBarWrapBackground:             darken($baseModBackground, 5%)                      !default;
$shortcodeSkillBarPercentageBackground:       rgba(0, 0, 0, 0.35)                                 !default;
$shortcodeSkillBarPercentageTextShadow:       0 1px 0 rgba(0, 0, 0, 0.75)                         !default;
$shortcodeMapBackground:                      $baseModBackground                                  !default;
$shortcodeMapBoxShadow:                       0 1px 3px rgba(0, 0, 0, 0.1)                        !default;
$shortcodePromptBackground:                   $baseModBackground                                  !default;
$shortcodePromoBackground:                    $baseModBackground                                  !default;
$shortcodePromoImgBorderBottom:               #eee                                                !default;
$shortcodeResponsiveVideoBackground:          $baseModBackground                                  !default;
$shortcodeResponsiveVideoBoxShadow:           0 1px 3px rgba(0, 0, 0, 0.1)                        !default;
$shortcodeColumnizeBorderColor:               #e5e5e5                                             !default;
$shortcodeCodeBackground:                     #f7f7f9                                             !default;
$shortcodeCodeBorder:                         #e1e1e8                                             !default;
$shortcodeRecentPostsBackground:              $baseModBackground                                  !default;
$shortcodeSkillbarBoxShadow:                  inset 0 1px 2px rgba(0, 0, 0, 0.15)                 !default;
$shortcodePricingTableColumnBoxShadow:        0 0 10px rgba(0, 0, 0, 0.125)                       !default;
$shortcodeCalloutBoxShadowOuter:              inset 0 1px 0 0 rgba(255, 255, 255, 0.95),
                                              0 1px 3px rgba(0, 0, 0, 0.05)                       !default;
$shortcodeCalloutBoxShadowInner:              inset 0 1px 3px rgba(0, 0, 0, 0.05),
                                              0 1px 0 0 rgba(255, 255, 255, 0.95)                 !default;
$shortcodeTOCBackground:                      $baseModBackground                                  !default;
$shortcodeTOCBoxShadow:                       0 1px 2px rgba(0, 0, 0, 0.1)                        !default;
$woocommerceQuantityButtonBackground:         #f5f5f5                                             !default;
$woocommerceQuantityButtonBackgroundHover:    #fafafa                                             !default;
$scrollTopColor:                              #272727                                             !default;



// Scaffolding
// =============================================================================

$bodyBackground:    #f2f2f2 !default;
$textColor:         #7a7a7a !default;



// Links
// =============================================================================

$linkColor:          $accentColor                                                                              !default;
$linkColorHover:     darken($linkColor, 15%)                                                                   !default;
$linkTransitions:    color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease !default;



// Typography
// =============================================================================

@import "../../../inc/font-stacks";


//
// Font families.
//

$sansFontFamily:     $lato     !default;
$monoFontFamily:     $consolas !default;
$altFontFamily:      $lato     !default;


//
// Base styles.
//

$baseFontSize:      62.5%           !default;
$baseLineHeight:    1.7             !default;
$baseFontWeight:    300             !default;
$baseFontFamily:    $sansFontFamily !default;
$baseMargin:        1.313em         !default;


//
// Heading styles.
//

$headingsFontFamily:    $altFontFamily !default;
$headingsFontWeight:    700            !default;
$headingsColor:         #272727        !default;



// Tables
// =============================================================================

$tableBackground:    transparent !default;
$tableBorder:        #ddd        !default;



// Buttons
// =============================================================================

//
// Base button styling.
//

$btnIntegrityBackground:    $accentColor              !default;
$btnIntegrityBorder:        darken($accentColor, 20%) !default;



// Forms and Alerts
// =============================================================================

//
// Base form styling.
//

$formBaseFontWeight:       300     !default;
$formActionsBackground:    #f5f5f5 !default;


//
// Inputs.
//

$inputBackground:            $white             !default;
$inputBorder:                #ddd               !default;
$inputBorderFocus:           rgba(0, 0, 0, 0.3) !default;
$inputBorderRadius:          4px                !default;
$inputDisabledBackground:    $grayLighter       !default;


//
// Box shadows.
//

$inputUneditableBoxShadow:    inset 0 1px 1px rgba(0, 0, 0, 0.075)                             !default;
$inputBoxShadowFocus:         inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(0, 0, 0, 0.2) !default;


//
// Fine-tune element alignment.
//

$controlGroupMarginTop:               0    !default;
$controlGroupMarginTopAfterLegend:    20px !default;
$controlGroupMarginBottom:            24px !default;
$controlLabelPaddingTop:              4px  !default;
$labelMarginBottom:                   2px  !default;
$legendMargin:                        0    !default;
$fileMarginTop:                       2px  !default;
$checkboxTopPosition:                 2px  !default;
$radioTopPosition:                    1px  !default;


//
// Form states and alert colors.
//

$warningText:          #c09853                                          !default;
$warningBackground:    #fcf8e3                                          !default;
$warningBorder:        darken(adjust-hue($warningBackground, -10), 13%) !default;

$errorText:            #b94a48                                       !default;
$errorBackground:      #f2dede                                       !default;
$errorBorder:          darken(adjust-hue($errorBackground, -10), 9%) !default;

$successText:          #468847                                          !default;
$successBackground:    #dff0d8                                          !default;
$successBorder:        darken(adjust-hue($successBackground, -10), 13%) !default;

$infoText:             #3a87ad                                     !default;
$infoBackground:       #d9edf7                                     !default;
$infoBorder:           darken(adjust-hue($infoBackground, 10), 9%) !default;

$mutedText:            $grayLight                !default;
$mutedBackground:      $grayLighter              !default;
$mutedBorder:          darken($grayLighter, 12%) !default;


//
// Alerts.
//

$alertPadding:          0.786em 2.25em 1em 1.15em !default;
$alertBlockPadding:     0.786em 1.15em 1em        !default;

$alertBorderRadius:     4px                                                                    !default;
$alertBoxShadow:        inset 0 1px 0 rgba(255, 255, 255, 0.8), 0 2px 3px rgba(0, 0, 0, 0.065) !default;

$alertLineHeight:       1.5                              !default;
$alertHeadingMargin:    0.05em 0 0.25em                  !default;
$alertTextShadow:       0 1px 0 rgba(255, 255, 255, 0.9) !default;



// Component Variables
// =============================================================================

//
// Input placeholder text.
//

$placeholderText:    #c5c5c5 !default;


//
// Navbar.
// 1. Base styles.
// 2. Link and text styles.
// 3. Button styles.
// 4. Brand styles.
//

$navbarHeight:                     90px                            !default; // 1
$navbarFixedSideWidth:             228px                           !default; // 1
$navbarFontSize:                   14px                            !default; // 1
$navbarLinkFontWeight:             500                             !default; // 1
$navbarBackground:                 $baseModBackground              !default; // 1
$navbarOuterBorder:                darken($baseModBackground, 20%) !default; // 1
$navbarLeftBoxShadow:              2px 0 4px rgba(0, 0, 0, 0.1)    !default; // 1
$navbarRightBoxShadow:             -2px 0 4px rgba(0, 0, 0, 0.1)   !default; // 1

$navbarLinkColor:                  #b7b7b7                       !default; // 2
$navbarLinkColorHover:             #272727                       !default; // 2
$navbarLinkColorActive:            $gray                         !default; // 2
$navbarLinkBackgroundHover:        transparent                   !default; // 2
$navbarLinkBackgroundActive:       darken($navbarBackground, 3%) !default; // 2
$navbarTopLinkBoxShadowHover:      inset 0 4px 0 0 $accentColor  !default; // 2
$navbarLeftLinkBoxShadowHover:     inset 8px 0 0 0 $accentColor  !default; // 2
$navbarRightLinkBoxShadowHover:    inset -8px 0 0 0 $accentColor !default; // 2

$navbarBtnColor:                   darken($navbarLinkColor, 15%)                               !default; // 3
$navbarBtnColorCollapsed:          $navbarLinkColor                                            !default; // 3
$navbarBtnBackground:              darken($navbarBackground, 0%)                               !default; // 3
$navbarBtnBackgroundHover:         darken($navbarBackground, 3%)                               !default; // 3
$navbarBtnTextShadow:              0 1px 1px rgba(255, 255, 255, 0.75)                         !default; // 3
$navbarBtnBoxShadow:               inset 0 0 0 rgba(0, 0, 0, 0), 0 1px 5px rgba(0, 0, 0, 0.25) !default; // 3
$navbarBtnBoxShadowHover:          inset 0 1px 4px rgba(0, 0, 0, 0.25)                         !default; // 3

$navbarBrandFontWeight:            700                              !default; // 4
$navbarBrandColor:                 $navbarLinkColorHover            !default; // 4
$navbarBrandTextShadow:            0 1px 0 rgba(255, 255, 255, 0.5) !default; // 4


//
// Dropdowns.
// 1. Base styles.
// 2. Positioning.
// 3. Links.
// 4. Dividers.
//

$dropdownBorder:                 1px solid rgba(0, 0, 0, 0.2)  !default; // 1
$dropdownPadding:                0.75em 0                      !default; // 1
$dropdownBackground:             $white                        !default; // 1
$dropdownBoxShadow:              0 3px 5px rgba(0, 0, 0, 0.25) !default; // 1

$dropdownFirstSide:              94%     !default; // 2
$dropdownTop:                    -0.75em !default; // 2
$dropdownOffset:                 98%     !default; // 2

$dropdownLinkPadding:            0.5em 1.6em           !default; // 3
$dropdownLinkColor:              $navbarLinkColor      !default; // 3
$dropdownLinkColorHover:         $navbarLinkColorHover !default; // 3
$dropdownLinkBackgroundHover:    rgba(0, 0, 0, 0.0175) !default; // 3


//
// Tooltips and popovers.
//

$tooltipArrowWidth:         5px     !default;
$tooltipArrowColor:         #272727 !default;
$tooltipColor:              $white  !default;
$tooltipBackground:         #272727 !default;

$popoverArrowWidth:         10px                           !default;
$popoverArrowColor:         $white                         !default;
$popoverArrowOuterWidth:    $popoverArrowWidth + 1         !default;
$popoverArrowOuterColor:    rgba(0, 0, 0, 0.25)            !default;
$popoverBackground:         $white                         !default;
$popoverTitleBackground:    darken($popoverBackground, 3%) !default;


//
// Colophon.
//

$colophonBackground:        $baseModBackground                       !default;
$colophonBorderTopRgba:     rgba(0, 0, 0, 0.085)                     !default;
$colophonFirstBorder:       lighten($navbarOuterBorder, 3%)          !default;
$colophonFirstBoxShadow:    0 -0.125em 0.25em 0 rgba(0, 0, 0, 0.075) !default;



// BuddyPress
// =============================================================================

$bpSpacingSm:         15px                                   !default;
$bpSpacingLg:         $bpSpacingSm * 3                       !default;
$bpCalcSpacingSm:     25px + $bpSpacingSm                    !default;
$bpCalcSpacingLg:     45px + $bpSpacingSm                    !default;
$bpBorderColor:       $shortcodeBorderColor                  !default;
$bpBorderWidth:       1px                                    !default;
$bpBorder:            $bpBorderWidth solid $bpBorderColor    !default;
$bpBoxShadowOuter:    0 1px 2px rgba(0, 0, 0, 0.075)         !default;
$bpBoxShadowInner:    inset -1px -1px rgba(255, 255, 255, 1) !default;
$bpAccentColor:       #fcfcfc                                !default;
$bpNavSubnavColor:    rgba(0, 0, 0, 0.35)                    !default;



// Grid
// =============================================================================

//
// Grid columns.
//

$gridColumns:    12 !default;


//
// Fixed.
//

$gridColumnWidth:    31px                                                                        !default;
$gridGutterWidth:    40px                                                                        !default;
$gridRowWidth:       ($gridColumns * $gridColumnWidth) + ($gridGutterWidth * ($gridColumns - 1)) !default;


//
// Fluid.
//

$fluidGridColumnWidth:    percentage($gridColumnWidth/$gridRowWidth) !default;
$fluidGridGutterWidth:    percentage($gridGutterWidth/$gridRowWidth) !default;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                // =============================================================================
// SCSS/SITE/STACKS/INTEGRITY/INC/_VARIABLES-DARK.SCSS
// -----------------------------------------------------------------------------
// SASS variables used specifically for Integrity.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Appearance
//   02. Scaffolding
//   03. Links
//   04. Typography
//       a. Font Families
//       b. Base Styles
//       c. Heading Styles
//   05. Tables
//   06. Buttons
//       a. Base Button Styling
//       b. Box Shadows
//   07. Forms and Alerts
//       a. Base Form Styling
//       b. Inputs
//       c. Box Shadows
//       d. Fine-Tune Element Alignment
//       e. Form States and Alert Colors
//       f. Alerts
//   08. Component Variables
//       a. Input Placeholder Text
//       b. Navbar
//       c. Dropdowns
//       d. Alerts
//       e. Tooltips and Popovers
//       f. Colophon
//   09. BuddyPress
//   10. Grid
//       a. Grid Columns
//       b. Fixed
//       c. Fluid
// =============================================================================

// Appearance
// =============================================================================

$skin:                                        'dark'                                             !default;

$baseBoxShadow:                               inset 0 -0.125em 0.5em 0 rgba(0, 0, 0, 0.85),
                                              0 3px 2px -2px rgba(255, 255, 255, 0.075)          !default;
$baseShortcodeBoxShadow:                      inset 0 -0.125em 0.5em 0 rgba(0, 0, 0, 0.85),
                                              0 3px 2px -2px rgba(255, 255, 255, 0.075)          !default;
$baseBoxShadowNavbar:                         0 0.15em 0.35em 0 rgba(0, 0, 0, .475)              !default;
$baseBoxShadowAccent:                         rgba(255, 255, 255, 0.085)                         !default;
$baseModBackground:                           lighten($black, 7%)                                !default;
$siteBackground:                              lighten($baseModBackground, 4%)                    !default;
$siteBorderColor:                             rgba(0, 0, 0, 0.95)                                !default;
$siteBoxShadow:                               0 0 8px rgba(0, 0, 0, 0.575)                       !default;
$accentColor:                                 #ff2a13                                            !default;
$entryFeaturedBoxShadow:                      none                                               !default;
$widgetBorderColor:                           $black                                             !default;
$widgetBorderColorRgba:                       rgba(0, 0, 0, 0.95)                                !default;
$widgetLiABlockBorderColorRgba:               rgba(0, 0, 0, 0.95)                                !default;
$widgetBlockLinkBorder:                       1px solid $widgetBorderColor                       !default;
$widgetUlBackground:                          $baseModBackground                                 !default;
$widgetUlBoxShadow:                           inset 0 0 0.35em 0 rgba(0, 0, 0, 0.85),
                                              0 3px 2px -2px rgba(255, 255, 255, 0.075)          !default;
$widgetUlChildrenBoxShadow:                   inset 0 1px 0 0 rgba(255, 255, 255, 0.045)         !default;
$widgetLiBoxShadow:                           0 1px 0 0 rgba(255, 255, 255, 0.045)               !default;
$widgetLiABlockBoxShadow:                     0 1px 0 0 rgba(255, 255, 255, 0.045)               !default;
$widgetLiBackgroundHover:                     transparent                                        !default;
$widgetLiABlockFirstChildBorderTop:           $black                                             !default;
$widgetTextShadow:                            0 -1px 0 rgba(0, 0, 0, 0.95)                       !default;
$widgetbarBackground:                         $black                                             !default;
$widgetbarColor:                              #727272                                            !default;
$widgetNavMenuCurrentItemBackground:          transparent                                        !default;
$widgetLiABlockBackgroundHover:               transparent                                        !default;
$widgetWooButtonStyledBoxShadow:              none                                               !default;
$widgetWooButtonStyledBackgroundHover:        rgba(0, 0, 0, 0.15)                                !default;
$widgetWooLayeredNavChosenBackground:         transparent                                        !default;
$widgetWooLayeredNavCountBackground:          lighten($widgetUlBackground, 3%)                   !default;
$widgetWooLayeredNavCountBoxShadow:           inset 0 1px 3px rgba(0, 0, 0, 0.985)               !default;
$widgetWooPriceFilterSliderBoxShadow:         inset 0 1px 2px 0 rgba(0, 0, 0, 0.5),
                                              0 1px 0 0 rgba(255, 255, 255, 0.075)               !default;
$widgetWooPriceFilterHandleBackground:        lighten($baseModBackground, 15%)                   !default;
$widgetWooPriceFilterRangeBoxShadow:          inset 0 1px 3px 0 rgba(0, 0, 0, 0.5)               !default;
$widgetWooPriceFilterHandleBoxShadow:         0 1px 3px rgba(0, 0, 0, 0.95)                      !default;
$contentDockBoxShadow:                        0 0.085em 0.85em 0 rgba(0, 0, 0, 0.565)            !default;
$contentDockCloseColor:                       lighten($baseModBackground, 5%)                    !default;
$tagColor:                                    lighten($baseModBackground, 25%)                   !default;
$tagColorRgba:                                rgba(255, 255, 255, 0.2)                           !default;
$tagColorHover:                               lighten($baseModBackground, 40%)                   !default;
$tagColorHoverRgba:                           rgba(255, 255, 255, 0.825)                         !default;
$tagColorActive:                              $tagColorHover                                     !default;
$tagColorActiveRgba:                          $tagColorHoverRgba                                 !default;
$tagBorderColor:                              #070707                                            !default;
$tagBorderColorRgba:                          rgba(0, 0, 0, 0.785)                               !default;
$tagBorderColorHover:                         $black                                             !default;
$tagBorderColorHoverRgba:                     rgba(0, 0, 0, 1)                                   !default;
$tagBorderColorActive:                        $black                                             !default;
$tagBorderColorActiveRgba:                    rgba(0, 0, 0, 1)                                   !default;
$tagBackgroundColorHover:                     transparent                                        !default;
$tagBackgroundColorHoverRgba:                 rgba(0, 0, 0, 0.175)                               !default;
$tagBackgroundColorActive:                    darken($baseModBackground, 2%)                     !default;
$tagBackgroundColorActiveRgba:                rgba(0, 0, 0, 0.5)                                 !default;
$tagBoxShadow:                                0 1px 1px rgba(255, 255, 255, 0.075)               !default;
$tagBoxShadowActive:                          inset 0 1px 2px rgba(0, 0, 0, 0.95), $tagBoxShadow !default;
$tagTextShadow:                               0 1px 0 rgba(0, 0, 0, 0.5)                         !default;
$boxedEntryBorder:                            #050505                                            !default;
$headerLandmarkBackgroundColor:               $black                                             !default;
$headerLandmarkBackgroundColorRgba:           rgba(0, 0, 0, 0.75)                                !default;
$headerLandmarkBoxShadow:                     rgba(255, 255, 255, 0.0575)                        !default;
$headerLandmarkSubColor:                      #5b5b5b                                            !default;
$breadcrumbBackground:                        rgba(255, 255, 255, 0.05)                          !default;
$breadcrumbBorderBottom:                      rgba(0, 0, 0, 0.825)                               !default;
$breadcrumbTextShadow:                        0 1px 0 rgba(0, 0, 0, 0.95)                        !default;
$breadcrumbBoxShadow:                         rgba(255, 255, 255, 0.065)                         !default;
$commentAvatarBoxShadow:                      inset 0 1px 2px 1px rgba(0, 0, 0, 0.85),
                                              0 1px 0 0 rgba(255, 255, 255, 0.1)                 !default;
$commentNumberBoxShadow:                      0 2px 5px rgba(0, 0, 0, 0.55)                      !default;
$commentByPostAuthorTextShadow:               0 1px 0 rgba(0, 0, 0, 0.85)                        !default;
$colophonBoxShadow:                           inset 0 1px 0 0 rgba(255, 255, 255, 0.075)         !default;
$mejsButtonBorderColor:                       $baseModBackground                                 !default;
$mejsGradientTop:                             darken($white, 70%)                                !default;
$mejsGradientBottom:                          darken($white, 85%)                                !default;
$searchFormIconColor:                         $black                                             !default;
$paginationItemBoxShadow:                     inset 0 0 0 rgba(0, 0, 0, 0),
                                              inset 0 0.1em 0.45em 0 rgba(0, 0, 0, 0.85)         !default;
$paginationCurrentItemBoxShadow:              0 2px 7px 0 rgba(0, 0, 0, 0.65)                    !default;
$paginationItemTextShadow:                    0 -1px 1px rgba(0, 0, 0, 0.95)                     !default;
$dropdownCollapseLinkColorHover:              $white                                             !default;
$dropdownCollapseLinkBackgroundHover:         #050505                                            !default;
$thumbnailBackground:                         #090909                                            !default;
$thumbnailBoxShadow:                          inset 0 -2px 3px rgba(0, 0, 0, 0.45)               !default;
$shortcodeBoxShadowOuter:                     none                                               !default;
$shortcodeBoxShadowInner:                     inset 0 -0.125em 0.5em 0 rgba(0, 0, 0, 0.85),
                                              0 3px 2px -2px rgba(255, 255, 255, 0.075)          !default;
$shortcodeBorderColor:                        $black                                             !default;
$shortcodeBorderColorRgba:                    rgba(0, 0, 0, 1)                                   !default;
$shortcodeAccordionContentBackground:         #0c0c0c                                            !default;
$shortcodeAccordionBeforeColor:               lighten($baseModBackground, 15%)                   !default;
$shortcodeTabsContentBackground:              #0c0c0c                                            !default;
$shortcodeTabsContentBoxShadow:               inset 0 -0.125em 0.5em 0 rgba(0, 0, 0, 0.85),
                                              0 3px 2px -2px rgba(255, 255, 255, 0.075)          !default;
$shortcodeTabNavLinkColor:                    darken(#666, 20%)                                  !default;
$shortcodeTabNavLinkColorHover:               #666                                               !default;
$shortcodeTabNavLinkBackground:               $baseModBackground                                 !default;
$shortcodeTabNavLinkBoxShadow:                inset 0 1px 0 0 rgba(255, 255, 255, 0.05)          !default;
$shortcodeSkillBarWrapBackground:             #0c0c0c                                            !default;
$shortcodeSkillBarPercentageBackground:       rgba(255, 255, 255, 0.35)                          !default;
$shortcodeSkillBarPercentageTextShadow:       0 1px 0 rgba(255, 255, 255, 0.35)                  !default;
$shortcodeMapBackground:                      #0c0c0c                                            !default;
$shortcodeMapBoxShadow:                       inset 0 -1px 4px rgba(0, 0, 0, 0.65)               !default;
$shortcodePromptBackground:                   #0c0c0c                                            !default;
$shortcodePromoBackground:                    #0c0c0c                                            !default;
$shortcodePromoImgBorderBottom:               $black                                             !default;
$shortcodeResponsiveVideoBackground:          #0c0c0c                                            !default;
$shortcodeResponsiveVideoBoxShadow:           inset 0 -1px 3px rgba(0, 0, 0, 0.45)               !default;
$shortcodeColumnizeBorderColor:               darken($baseModBackground, 4%)                     !default;
$shortcodeCodeBackground:                     #0c0c0c                                            !default;
$shortcodeCodeBorder:                         $black                                             !default;
$shortcodeRecentPostsBackground:              #0c0c0c                                            !default;
$shortcodeSkillbarBoxShadow:                  inset 0 2px 3px rgba(0, 0, 0, 0.65)                !default;
$shortcodePricingTableColumnBoxShadow:        0 0 15px rgba(0, 0, 0, 0.725)                      !default;
$shortcodeCalloutBoxShadowOuter:              inset 0 -1px 3px 0 rgba(0, 0, 0, 0.65)             !default;
$shortcodeCalloutBoxShadowInner:              inset 0 1px 0 0 rgba(255, 255, 255, 0.035),
                                              0 1px 2px rgba(0, 0, 0, 0.45)                      !default;
$shortcodeTOCBackground:                      #0c0c0c                                            !default;
$shortcodeTOCBoxShadow:                       inset 0 -1px 3px rgba(0, 0, 0, 0.45)               !default;
$woocommerceQuantityButtonBackground:         lighten($baseModBackground, 10%)                   !default;
$woocommerceQuantityButtonBackgroundHover:    lighten($baseModBackground, 15%)                   !default;
$scrollTopColor:                              $white                                             !default;



// Scaffolding
// =============================================================================

$bodyBackground:    #25292a !default;
$textColor:         #666    !default;



// Links
// =============================================================================

$linkColor:          $accentColor                                                                              !default;
$linkColorHover:     darken($linkColor, 15%)                                                                   !default;
$linkTransitions:    color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease !default;



// Typography
// =============================================================================

@import "../../../inc/font-stacks";


//
// Font families.
//

$sansFontFamily:     $lato     !default;
$monoFontFamily:     $consolas !default;
$altFontFamily:      $lato     !default;


//
// Base styles.
//

$baseFontSize:      62.5%           !default;
$baseLineHeight:    1.7             !default;
$baseFontWeight:    300             !default;
$baseFontFamily:    $sansFontFamily !default;
$baseMargin:        1.313em         !default;


//
// Heading styles.
//

$headingsFontFamily:    $altFontFamily !default;
$headingsFontWeight:    700            !default;
$headingsColor:         $white         !default;



// Tables
// =============================================================================

$tableBackground:    transparent                     !default;
$tableBorder:        lighten($baseModBackground, 5%) !default;



// Buttons
// =============================================================================

//
// Base button styling.
//

$btnIntegrityBackground:    $accentColor                         !default;
$btnIntegrityBorder:        darken($accentColor, 20%)            !default;



// Forms and Alerts
// =============================================================================

//
// Base form styling.
//

$formBaseFontWeight:       300     !default;
$formActionsBackground:    #f5f5f5 !default;


//
// Inputs.
//

$inputBackground:            lighten($baseModBackground, 7%) !default;
$inputBorder:                darken($baseModBackground, 2%)  !default;
$inputBorderFocus:           rgba(0, 0, 0, 0.85)             !default;
$inputBorderRadius:          4px                             !default;
$inputDisabledBackground:    $grayLighter                    !default;


//
// Box shadows.
//

$inputUneditableBoxShadow:    inset 0 1px 1px rgba(0, 0, 0, 0.075)                              !default;
$inputBoxShadowFocus:         inset 0 1px 1px rgba(0, 0, 0, 0.375), 0 0 8px rgba(0, 0, 0, 0.35) !default;


//
// Fine-tune element alignment.
//

$controlGroupMarginTop:               0    !default;
$controlGroupMarginTopAfterLegend:    20px !default;
$controlGroupMarginBottom:            24px !default;
$controlLabelPaddingTop:              4px  !default;
$labelMarginBottom:                   2px  !default;
$legendMargin:                        0    !default;
$fileMarginTop:                       2px  !default;
$checkboxTopPosition:                 2px  !default;
$radioTopPosition:                    1px  !default;


//
// Form states and alert colors.
//

// $warningText:          darken(#f1c40f, 25%)           !default;
// $warningBackground:    #0c0c0c                        !default;
// $warningBorder:        darken($baseModBackground, 8%) !default;

// $errorText:            darken(#e74c3c, 25%)           !default;
// $errorBackground:      #0c0c0c                        !default;
// $errorBorder:          darken($baseModBackground, 8%) !default;

// $successText:          darken(#2ecc71, 25%)           !default;
// $successBackground:    #0c0c0c                        !default;
// $successBorder:        darken($baseModBackground, 8%) !default;

// $infoText:             darken(#3498db, 25%)           !default;
// $infoBackground:       #0c0c0c                        !default;
// $infoBorder:           darken($baseModBackground, 8%) !default;

// $mutedText:            $gray                          !default;
// $mutedBackground:      #0c0c0c                        !default;
// $mutedBorder:          darken($baseModBackground, 8%) !default;

$warningText:          #c09853                                          !default;
$warningBackground:    #fcf8e3                                          !default;
$warningBorder:        darken(adjust-hue($warningBackground, -10), 40%) !default;

$errorText:            #b94a48                                        !default;
$errorBackground:      #f2dede                                        !default;
$errorBorder:          darken(adjust-hue($errorBackground, -10), 36%) !default;

$successText:          #468847                                          !default;
$successBackground:    #dff0d8                                          !default;
$successBorder:        darken(adjust-hue($successBackground, -10), 40%) !default;

$infoText:             #3a87ad                                      !default;
$infoBackground:       #d9edf7                                      !default;
$infoBorder:           darken(adjust-hue($infoBackground, 10), 36%) !default;

$mutedText:            $grayLight                !default;
$mutedBackground:      $grayLighter              !default;
$mutedBorder:          darken($grayLighter, 40%) !default;


//
// Alerts.
//

// $alertPadding:          0.786em 2.25em 1em 1.15em !default;
// $alertBlockPadding:     0.786em 1.15em 1em        !default;

// $alertBorderRadius:     4px                                                                        !default;
// $alertBoxShadow:        inset 0 -1px 4px rgba(0, 0, 0, 0.65), 0 1px 3px rgba(255, 255, 255, 0.075) !default;

// $alertLineHeight:       1.5                           !default;
// $alertHeadingMargin:    0.05em 0 0.25em               !default;
// $alertTextShadow:       0 1px 1px rgba(0, 0, 0, 0.85) !default;

$alertPadding:          0.786em 2.25em 1em 1.15em !default;
$alertBlockPadding:     0.786em 1.15em 1em        !default;

$alertBorderRadius:     4px                                                                    !default;
$alertBoxShadow:        inset 0 1px 0 rgba(255, 255, 255, 0.8), 0 2px 7px rgba(0, 0, 0, 0.965) !default;

$alertLineHeight:       1.5                              !default;
$alertHeadingMargin:    0.05em 0 0.25em                  !default;
$alertTextShadow:       0 1px 0 rgba(255, 255, 255, 0.9) !default;



// Component Variables
// =============================================================================

//
// Input placeholder text.
//

$placeholderText:    $grayLight !default;


//
// Navbar.
// 1. Base styles.
// 2. Link and text styles.
// 3. Button styles.
// 4. Brand styles.
//

$navbarHeight:                     90px                            !default; // 1
$navbarFixedSideWidth:             228px                           !default; // 1
$navbarFontSize:                   14px                            !default; // 1
$navbarLinkFontWeight:             500                             !default; // 1
$navbarBackground:                 $baseModBackground              !default; // 1
$navbarOuterBorder:                darken($baseModBackground, 25%) !default; // 1
$navbarLeftBoxShadow:              2px 0 4px rgba(0, 0, 0, 0.285)  !default; // 1
$navbarRightBoxShadow:             -2px 0 4px rgba(0, 0, 0, 0.285) !default; // 1

$navbarLinkColor:                  #bbb                          !default; // 2
$navbarLinkColorHover:             $white                        !default; // 2
$navbarLinkColorActive:            $gray                         !default; // 2
$navbarLinkBackgroundHover:        transparent                   !default; // 2
$navbarLinkBackgroundActive:       darken($navbarBackground, 3%) !default; // 2
$navbarTopLinkBoxShadowHover:      inset 0 4px 0 0 $accentColor  !default; // 2
$navbarLeftLinkBoxShadowHover:     inset 8px 0 0 0 $accentColor  !default; // 2
$navbarRightLinkBoxShadowHover:    inset -8px 0 0 0 $accentColor !default; // 2

$navbarBtnColor:                   $navbarLinkColor                                         !default; // 3
$navbarBtnColorCollapsed:          lighten($navbarLinkColor, 15%)                           !default; // 3
$navbarBtnBackground:              darken($navbarBackground, 0%)                            !default; // 3
$navbarBtnBackgroundHover:         darken($navbarBackground, 3%)                            !default; // 3
$navbarBtnTextShadow:              0 1px 1px rgba(0, 0, 0, 0.75)                            !default; // 3
$navbarBtnBoxShadow:               inset 0 0 0 rgba(0, 0, 0, 0), 0 1px 4px rgba(0, 0, 0, 1) !default; // 3
$navbarBtnBoxShadowHover:          inset 0 1px 4px rgba(0, 0, 0, 0.95)                      !default; // 3

$navbarBrandFontWeight:            700                              !default; // 4
$navbarBrandColor:                 $navbarLinkColorHover            !default; // 4
$navbarBrandTextShadow:            0 1px 0 rgba(255, 255, 255, 0.5) !default; // 4


//
// Dropdowns.
// 1. Base styles.
// 2. Positioning.
// 3. Links.
// 4. Dividers.
//

$dropdownBorder:                 1px solid rgba(0, 0, 0, 0.2)  !default; // 1
$dropdownPadding:                0.75em 0                      !default; // 1
$dropdownBackground:             $baseModBackground            !default; // 1
$dropdownBoxShadow:              0 2px 4px rgba(0, 0, 0, 0.95) !default; // 1

$dropdownFirstSide:              94%     !default; // 2
$dropdownTop:                    -0.75em !default; // 2
$dropdownOffset:                 98%     !default; // 2

$dropdownLinkPadding:            0.5em 1.6em           !default; // 3
$dropdownLinkColor:              $navbarLinkColor      !default; // 3
$dropdownLinkColorHover:         $navbarLinkColorHover !default; // 3
$dropdownLinkBackgroundHover:    rgba(0, 0, 0, 0.575)  !default; // 3


//
// Tooltips and popovers.
//

$tooltipArrowWidth:         5px     !default;
$tooltipArrowColor:         #353535 !default;
$tooltipColor:              $white  !default;
$tooltipBackground:         #353535 !default;

$popoverArrowWidth:         10px                            !default;
$popoverArrowColor:         lighten($baseModBackground, 9%) !default;
$popoverArrowOuterWidth:    $popoverArrowWidth + 1          !default;
$popoverArrowOuterColor:    rgba(0, 0, 0, 0.25)             !default;
$popoverBackground:         lighten($baseModBackground, 9%) !default;
$popoverTitleBackground:    darken($popoverBackground, 6%)  !default;


//
// Colophon.
//

$colophonBackground:        $baseModBackground                         !default;
$colophonBorderTopRgba:     rgba(0, 0, 0, 0.75)                        !default;
$colophonFirstBorder:       $navbarOuterBorder                         !default;
$colophonFirstBoxShadow:    inset 0 1px 0 0 rgba(255, 255, 255, 0.075) !default;



// BuddyPress
// =============================================================================

$bpSpacingSm:         15px                                       !default;
$bpSpacingLg:         $bpSpacingSm * 3                           !default;
$bpCalcSpacingSm:     25px + $bpSpacingSm                        !default;
$bpCalcSpacingLg:     45px + $bpSpacingSm                        !default;
$bpBorderColor:       $shortcodeBorderColor                      !default;
$bpBorderWidth:       1px                                        !default;
$bpBorder:            $bpBorderWidth solid $bpBorderColor        !default;
$bpBoxShadowOuter:    0 1px 2px rgba(0, 0, 0, 0.35)              !default;
$bpBoxShadowInner:    inset -1px -1px rgba(255, 255, 255, 0.025) !default;
$bpAccentColor:       #0c0c0c                                    !default;
$bpNavSubnavColor:    rgba(255, 255, 255, 0.1)                   !default;



// Grid
// =============================================================================

//
// Grid columns.
//

$gridColumns:    12 !default;


//
// Fixed.
//

$gridColumnWidth:    31px                                                                        !default;
$gridGutterWidth:    40px                                                                        !default;
$gridRowWidth:       ($gridColumns * $gridColumnWidth) + ($gridGutterWidth * ($gridColumns - 1)) !default;


//
// Fluid.
//

$fluidGridColumnWidth:    percentage($gridColumnWidth/$gridRowWidth) !default;
$fluidGridGutterWidth:    percentage($gridGutterWidth/$gridRowWidth) !default;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  // =============================================================================
// SCSS/SITE/STACKS/INTEGRITY/_WOOCOMMERCE.SCSS
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
    background-color: $baseModBackground;
    border-radius: 4px;
    @include box-shadow(#{$baseBoxShadow});

    &.first {
      clear: both;
    }

    &.last {
      margin-right: 0;
    }

    .onsale {
      top: 6px;
      left: 6px;
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
      padding: 15px;
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
        left: 0;
        right: 0;
        margin: 0;
        padding: 0.45em 0.5em 0.575em;
        display: block;
        @include font-size(1.4);
        @include opacity(0);
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
  .entry-wrap {
    padding: 60px;
    @include clearfix();
    @include break(cubs) {
      padding: 36px;
    }
    @include break(baby-bear) {
      padding: 25px;
    }
  }

  .entry-wrap > .entry-content:first-child {
    margin: 0;
    @include clearfix();
  }

  div.product {
    .images {
      position: relative;
      float: left;
      width: 48%;
      @include break(middle-bear) {
        width: 100%;
      }

      .onsale {
        top: 12px;
        left: 12px;
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
        border: 1px solid $shortcodeBorderColorRgba;
        border-radius: 0 0 4px 4px;
        @include box-shadow(#{$shortcodeTabsContentBoxShadow});

        .entry-featured {
          border-bottom: 1px solid $shortcodeBorderColor;
          border-bottom: 1px solid $shortcodeBorderColorRgba;
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
        border: 1px solid $tableBorder;
        padding: 15px;
        background-color: $woocommerceQuantityButtonBackground;
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
      font-size: 81.25%;
      @include clearfix();

      &:last-child {
        margin-bottom: 0;
      }

      a {
        display: block;
        margin-bottom: 2px;
        border-bottom: 0;
        font-size: 135.7%;

        img {
          @include img_thumbnail();
          padding: 3px !important;
          float: left;
          width: 65px;
          margin-right: 0.65em;
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
    margin-bottom: 0;
    border: 1px solid $widgetBorderColor;
    border: 1px solid $widgetBorderColorRgba;
    border-bottom: 0;
    padding: 7px;
    @include font-size(1.1);
    line-height: 1.1;
    text-align: center;
    text-transform: uppercase;
    border-radius: 6px 6px 0 0;
  }

  .buttons {
    border: 1px solid $widgetBorderColor;
    border: 1px solid $widgetBorderColorRgba;
    border-radius: 0 0 6px 6px;
    @include clearfix();
    @include box-shadow(#{$widgetWooButtonStyledBoxShadow});

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
        background-color: $widgetWooButtonStyledBackgroundHover;
      }

      &:first-child {
        border-radius: 0 0 0 6px;
        border-right: 1px solid $widgetBorderColor;
        border-right: 1px solid $widgetBorderColorRgba;
      }

      &:last-child {
        border-radius: 0 0 6px 0;
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
    background-color: $widgetWooLayeredNavChosenBackground;
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
      background-color: $widgetWooLayeredNavCountBackground;
      border-radius: 100%;
      @include box-shadow(#{$widgetWooLayeredNavCountBoxShadow});
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
    @include box-shadow(#{$widgetWooPriceFilterSliderBoxShadow});

    .ui-slider-handle {
      position: absolute;
      top: 50%;
      width: 21px;
      height: 21px;
      margin-top: -11px;
      cursor: pointer;
      background-color: $widgetWooPriceFilterHandleBackground;
      outline: none;
      border-radius: 1em;
      z-index: 2;
      @include box-shadow(#{$widgetWooPriceFilterHandleBoxShadow});

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
      background-color: $accentColor;
      border-radius: 1em;
      z-index: 1;
      @include box-shadow(#{$widgetWooPriceFilterRangeBoxShadow});
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
      border: 1px solid $widgetBorderColorRgba;
      padding: 7px;
      @include font-size(1.1);
      line-height: 1.1;
      text-align: center;
      text-shadow: none;
      background-color: transparent;
      @include box-shadow(#{$widgetWooButtonStyledBoxShadow});
      @include text-overflow();
    }

    .button {
      color: $textColor;
      border-radius: 4px 0 0 4px;

      &:hover {
        background-color: $widgetWooButtonStyledBackgroundHover;
      }
    }

    .price_label {
      width: 50%;
      border-left: 0;
      border-radius: 0 4px 4px 0;
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
      color: $accentColor;
    }
  }
}



// Sale Badge
// =============================================================================

.woocommerce,
.woocommerce-page {
  .onsale {
    position: absolute;
    display: block;
    width: 42px;
    height: 42px;
    @include font-size(1.4);
    letter-spacing: 0;
    line-height: 40px;
    text-align: center;
    text-shadow: 0 1px 1px rgba(0, 0, 0, 0.6);
    color: $white;
    border-radius: 100%;
    background-color: $accentColor;
    z-index: 1;
    @include rotate(-10deg);
    @include box-shadow(#{inset 0 1px 3px rgba(0, 0, 0, 0.45)});
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
    line-height: 1em;

    &:before {
      content: "\73\73\73\73\73";
      position: absolute;
      top: 0;
      left: 0;
      float: left;
      color: $accentColor;
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
        color: $accentColor;
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
      top: 13px;
      right: 13px;
      padding: 7px;
      border-radius: 3px;
      background-color: $black;
      background-color: rgba(0, 0, 0, 0.5);
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
            width: 6.25em;
            padding: 0 1em;
            position: absolute;
            top: 0;
            right: -8.5em;
            font-size: 0.5em;
            text-align: center;
            color: $textColor;
            background-color: darken($baseModBackground, 10%);
            border-radius: 100em;
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

    .x-comments-list {
      .x-comment-img {
        .avatar-wrap {
          &:before {
            display: none;
          }

          .avatar {
            width: 60px;
            border-radius: 0;
          }
        }

        @include break(middle-bear) {
          display: none;
        }
      }

      article.comment {
        border: 1px solid $shortcodeBorderColor;
        border: 1px solid $shortcodeBorderColorRgba;
        border-radius: 2px;
        @include box-shadow(#{$thumbnailBoxShadow});
        @include break(middle-bear) {
          margin-left: 0;
        }
      }

      .x-comment-header {
        .star-rating-container {
          margin-top: 4px;
        }
      }
    }
  }
}



// Results Count and Ordering
// =============================================================================

.woocommerce-result-count {
  float: right;
  height: 2.65em;
  padding: 0 7px;
  border: 1px solid $widgetBorderColor;
  border: 1px solid $widgetBorderColorRgba;
  line-height: 2.5em;
  @include font-size(1.1);
  border-radius: 4px;
  @include box-shadow(#{$widgetWooButtonStyledBoxShadow});
}

.woocommerce-ordering {
  float: left;

  select {
    width: 100%;
    margin-bottom: 0;
    @include font-size(1.1);
    @include box-shadow(#{$widgetWooButtonStyledBoxShadow});
  }
}

.woocommerce-result-count,
.woocommerce-ordering {
  @include break(baby-bear) {
    float: none;
  }
}



// Messages and Errors
// =============================================================================

.woocommerce-message,
.woocommerce-error,
.woocommerce-info {
  a {
    color: inherit;
    text-decoration: underline;

    &:hover {
      color: inherit;
    }
  }
}

.woocommerce-message {
  .button {
    display: block;
    margin: 0 0 0 40px;
    border: 0;
    padding: 0;
    float: right;
    font-size: inherit;
    font-weight: inherit;
    line-height: inherit;
    color: inherit;
    text-align: inherit;
    text-shadow: inherit;
    background-color: transparent;
    @include box-shadow(#{none});

    &:hover {
      margin: 0;
      border: 0;
      padding: 0;
      color: inherit;
      text-decoration: underline;
      text-shadow: inherit;
      background-color: transparent;
      @include box-shadow(#{none});
    }
  }
}

.woocommerce-error {
  margin: $baseMargin 0;
  list-style: none;
}

.woocommerce-info {
  margin-bottom: $baseMargin;
}



// Form Feedback States
// =============================================================================

.woocommerce-invalid {
  input {
    color: $errorText;
    border-color: $errorBorder;
    background-color: lighten($errorBackground, 5%);
  }
}

.woocommerce-validated {
  input {
    color: $successText;
    border-color: $successBorder;
    background-color: lighten($successBackground, 7.5%);
  }
}



// Select2
// =============================================================================

.woocommerce,
.woocommerce-page {
  .select2-container {
    display: block !important;
    margin: 0 0 $baseMargin;
  }
}



// Button Icons
// =============================================================================

.woocommerce,
.woocommerce-page {
  .button {
    &.product_type_simple:before,
    &.product_type_variable:before,
    &.single_add_to_cart_button:before {
      @include font-awesome();
    }

    &.product_type_simple:before,
    &.single_add_to_cart_button:before {
      content: "\f07a\0020";
    }

    &.product_type_variable:before {
      content: "\f14a\0020";
    }
  }
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                      // =============================================================================
// SCSS/SITE/STACKS/INTEGRITY/_GRAVITY-FORMS.SCSS
// -----------------------------------------------------------------------------
// Additional styling for Gravity Forms.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Base Styles
//   02. Headings and Labels
//   03. Inputs and Containers
//   04. Alerts
// =============================================================================

// Base Styles
// =============================================================================

//
// Form wrapper.
//

body .gform_wrapper {
  max-width: 100%;
  margin: 0 0 $baseMargin;
}


//
// Form heading.
//

body .gform_wrapper .gform_heading {
  width: 100%;
}


//
// Field wrapper.
//

body .gform_wrapper .top_label .gfield,
body .gform_wrapper .top_label li.gsection.gf_scroll_text {
  margin-bottom: 1em !important;
}

body .gform_wrapper .left_label .gfield,
body .gform_wrapper .left_label li.gsection.gf_scroll_text,
body .gform_wrapper .right_label .gfield,
body .gform_wrapper .right_label li.gsection.gf_scroll_text {
  margin-bottom: 1.5em !important;
}


//
// Section title.
//

body .gform_wrapper .top_label .gsection,
body .gform_wrapper .top_label li.gfield.gf_left_half + li.gsection,
body .gform_wrapper .top_label li.gfield.gf_right_half + li.gsection {
  margin-top: 0 !important;
  margin-bottom: 4px !important;
  border-bottom: 1px solid $shortcodeBorderColor;
  border-bottom: 1px solid $shortcodeBorderColorRgba;
  padding: 28px 0 4px;
}

body .gform_wrapper .left_label .gsection,
body .gform_wrapper .left_label li.gfield.gf_left_half + li.gsection,
body .gform_wrapper .left_label li.gfield.gf_right_half + li.gsection,
body .gform_wrapper .right_label .gsection,
body .gform_wrapper .right_label li.gfield.gf_left_half + li.gsection,
body .gform_wrapper .right_label li.gfield.gf_right_half + li.gsection {
  margin-top: 0 !important;
  margin-bottom: 10px !important;
  border-bottom: 1px solid $shortcodeBorderColor;
  border-bottom: 1px solid $shortcodeBorderColorRgba;
  padding: 28px 0 6px;
}


//
// Form footer.
//

body .gform_wrapper .gform_footer {
  margin: $baseMargin 0 0;
  padding: 0;
}

body .gform_wrapper .gform_footer.left_label {
  padding: 0 0 0 30%;
  @include break(middle-bear) {
    padding: 0;
  }
}

body .gform_wrapper .gform_footer.right_label {
  padding: 0 30% 0 0;
  @include break(middle-bear) {
    padding: 0;
  }
}



// Headings and Labels
// =============================================================================

//
// Main headings.
//

body .gform_wrapper h3.gform_title,
body .gform_wrapper h2.gsection_title,
body .gform_wrapper .gsection_description {
  width: 100%;
}

body .gform_wrapper h3.gform_title {
  margin: 0 0 0.2em;
  font-size: 200%;
  @include break(baby-bear) {
    font-size: 175%;
  }
}

body .gform_wrapper h2.gsection_title {
  font-size: 125%;
}


//
// Input labels.
//

body .gform_wrapper .top_label .gfield_label {
  margin: 0;
}

body .gform_wrapper .left_label .gfield_label,
body .gform_wrapper .right_label .gfield_label {
  width: 30%;
  padding: 0 4% 0 0;
  font-size: 82.5%;
  @include break(middle-bear) {
    margin-bottom: 0;
  }
}

body .gform_wrapper .left_label .gfield_label {
  float: left;
  margin-right: 0;
  padding: 0 4% 0 0;
  @include break(middle-bear) {
    float: none;
    width: 100%;
    padding: 0;
  }
}

body .gform_wrapper .right_label .gfield_label {
  float: right;
  margin-left: 0;
  padding: 0 0 0 4%;
  text-align: right;
  @include break(middle-bear) {
    float: none;
    width: 100%;
    padding: 0;
    text-align: left;
  }
}

body .gform_wrapper li.gfield.gf_list_2col label.gfield_label,
body .gform_wrapper li.gfield.gf_list_3col label.gfield_label,
body .gform_wrapper li.gfield.gf_list_4col label.gfield_label,
body .gform_wrapper li.gfield.gf_list_5col label.gfield_label,
body .gform_wrapper li.gfield.gf_list_inline label.gfield_label {
  margin-top: 0;
}


//
// Description text.
//

body .gform_wrapper .ginput_complex label,
body .gform_wrapper .description,
body .gform_wrapper .gfield_description,
body .gform_wrapper .gsection_description,
body .gform_wrapper .instruction {
  font-family: inherit;
  font-size: 67.5%;
  @include opacity(0.7);
}

body .gform_wrapper .description,
body .gform_wrapper .gsection_description {
  padding: 5px 0 0;
}

body .gform_wrapper .gfield_description {
  padding: 2px 0 0;
}

body .gform_wrapper .description_above .gfield_description {
  padding: 0 0 2px;
}

body .gform_wrapper .left_label .instruction,
body .gform_wrapper .left_label .gfield_description,
body .gform_wrapper .left_label li.gsection.gf_scroll_text {
  width: 70% !important;
  margin-left: 30% !important;
  margin-right: 0 !important;
  @include break(middle-bear) {
    width: 100% !important;
    margin-left: 0 !important;
  }
}

body .gform_wrapper .right_label .instruction,
body .gform_wrapper .right_label .gfield_description,
body .gform_wrapper .right_label li.gsection.gf_scroll_text {
  width: 70% !important;
  margin-left: 0 !important;
  margin-right: 30% !important;
  @include break(middle-bear) {
    width: 100% !important;
    margin-right: 0 !important;
  }
}

body .gform_wrapper .ginput_complex label,
body .gform_wrapper .gfield_time_hour label,
body .gform_wrapper .gfield_time_minute label,
body .gform_wrapper .gfield_date_month label,
body .gform_wrapper .gfield_date_day label,
body .gform_wrapper .gfield_date_year label,
body .gform_wrapper .instruction {
  margin: 0;
}


//
// Checkbox and radio labels.
//

body .gform_wrapper .gfield_radio li label,
body .gform_wrapper .gfield_checkbox li label {
  @include font-size(1.3);
}


//
// Focus states.
//

body .gform_wrapper .ginput_full input:focus + label,
body .gform_wrapper .ginput_left input:focus + label,
body .gform_wrapper .ginput_right input:focus + label {
  font-weight: inherit
}

body .gform_wrapper ul.gfield_radio li input[type="radio"]:checked + label,
body .gform_wrapper ul.gfield_checkbox li input[type="checkbox"]:checked + label {
  font-weight: inherit;
}


//
// Browser adjustments.
//

body .gform_wrapper.gf_browser_chrome .gfield_checkbox li label,
body .gform_wrapper.gf_browser_chrome .gfield_radio li label,
body .gform_wrapper.gf_browser_safari .gfield_checkbox li label,
body .gform_wrapper.gf_browser_safari .gfield_radio li label {
  margin-top: 2px;
}



// Inputs and Containers
// =============================================================================

//
// Base style.
//

body .gform_wrapper input[type=text],
body .gform_wrapper input[type=url],
body .gform_wrapper input[type=email],
body .gform_wrapper input[type=tel],
body .gform_wrapper input[type=number],
body .gform_wrapper input[type=password],
body .gform_wrapper select,
body .gform_wrapper textarea {
  display: inline-block;
  height: 2.65em;
  margin: 3px 0;
  padding: 0 0.65em;
  line-height: 2.65em;
  @include font-size(1.3);
}

body .gform_wrapper select[multiple],
body .gform_wrapper select[size] {
  height: auto;
}


//
// Browser adjustments.
//

body .gform_wrapper.gf_browser_gecko select {
  padding: 0.45em 0.65em;
}


//
// All containers.
//

body .gform_wrapper .top_label li.gfield.gf_left_half,
body .gform_wrapper .top_label li.gfield.gf_left_third,
body .gform_wrapper .top_label li.gfield.gf_middle_third {
  margin-right: 4%;
}

body .gform_wrapper .top_label li.gfield.gf_left_half,
body .gform_wrapper .top_label li.gfield.gf_right_half,
body .gform_wrapper .top_label li.gfield.gf_left_third,
body .gform_wrapper .top_label li.gfield.gf_middle_third,
body .gform_wrapper .top_label li.gfield.gf_right_third {
  float: left;
  margin-left: 0 !important;
}

body .gform_wrapper li.gfield .ginput_complex .ginput_full,
body .gform_wrapper li.gfield .ginput_complex .ginput_left,
body .gform_wrapper li.gfield .ginput_complex .ginput_right {
  margin-bottom: 8px;
}

body .gform_wrapper li.gfield .ginput_complex .ginput_full + .ginput_left,
body .gform_wrapper li.gfield .ginput_complex .ginput_left + .ginput_left,
body .gform_wrapper li.gfield .ginput_complex .ginput_right + .ginput_left {
  clear: left;
}

body .gform_wrapper li.gfield .ginput_complex .ginput_full + .ginput_right,
body .gform_wrapper li.gfield .ginput_complex .ginput_left + .ginput_right,
body .gform_wrapper li.gfield .ginput_complex .ginput_right + .ginput_right {
  clear: right;
}


//
// Half size containers.
//

body .gform_wrapper .top_label input.medium,
body .gform_wrapper .top_label select.medium,
body .gform_wrapper .top_label li.gfield.gf_left_half,
body .gform_wrapper .top_label li.gfield.gf_right_half {
  width: 48%;
  @include break(baby-bear) {
    float: none;
    width: 100%;
  }
}

body .gform_wrapper .ginput_complex .ginput_left,
body .gform_wrapper .ginput_complex .ginput_right,
body .gform_wrapper .gfield_error .ginput_complex .ginput_left,
body .gform_wrapper .gfield_error .ginput_complex .ginput_right {
  width: 48%;
  @include break(middle-bear) {
    float: none;
    width: 100%;
  }
}


//
// Third size containers.
//

body .gform_wrapper .top_label li.gfield.gf_left_third,
body .gform_wrapper .top_label li.gfield.gf_middle_third,
body .gform_wrapper .top_label li.gfield.gf_right_third {
  width: 30.66667%;
  @include break(baby-bear) {
    float: none;
    width: 100%;
  }
}


//
// Radio and checkboxs.
//

body .gform_wrapper .gfield_radio li,
body .gform_wrapper .gfield_checkbox li {
  margin-bottom: 0 !important;
}

body .gform_wrapper .gfield_radio li input,
body .gform_wrapper .gfield_checkbox li input {
  margin-left: 1px;
}


//
// List columns.
//

body .gform_wrapper li.gfield.gf_list_2col ul.gfield_checkbox li,
body .gform_wrapper li.gfield.gf_list_2col ul.gfield_radio li {
  padding-left: 2.5% !important;
  @include break(baby-bear) {
    float: none;
    width: 100%;
    padding-left: 0 !important;
  }
}

body .gform_wrapper li.gfield.gf_list_3col ul.gfield_checkbox li,
body .gform_wrapper li.gfield.gf_list_3col ul.gfield_radio li {
  padding-left: 2.5% !important;
  @include break(middle-bear) {
    float: none;
    width: 100%;
    padding-left: 0 !important;
  }
}

body .gform_wrapper li.gfield.gf_list_2col ul.gfield_checkbox li:nth-child(2n+1),
body .gform_wrapper li.gfield.gf_list_2col ul.gfield_radio li:nth-child(2n+1),
body .gform_wrapper li.gfield.gf_list_3col ul.gfield_checkbox li:nth-child(3n+1),
body .gform_wrapper li.gfield.gf_list_3col ul.gfield_radio li:nth-child(3n+1) {
  padding-left: 0 !important;
}


//
// Small inputs.
//

body .gform_wrapper .top_label input.small,
body .gform_wrapper .top_label select.small,
body .gform_wrapper .left_label input.small,
body .gform_wrapper .left_label select.small,
body .gform_wrapper .right_label input.small,
body .gform_wrapper .right_label select.small {
  width: 25%;
  @include break(baby-bear) {
    width: 100%;
  }
}


//
// Left and right label configurations.
//

body .gform_wrapper .left_label input.medium,
body .gform_wrapper .left_label select.medium,
body .gform_wrapper .right_label input.medium,
body .gform_wrapper .right_label select.medium {
  width: 33.635%;
  @include break(middle-bear) {
    width: 100%;
  }
}

body .gform_wrapper .left_label div.ginput_complex,
body .gform_wrapper .right_label div.ginput_complex,
body .gform_wrapper .left_label textarea.textarea,
body .gform_wrapper .right_label textarea.textarea,
body .gform_wrapper .left_label input.large,
body .gform_wrapper .left_label select.large,
body .gform_wrapper .right_label input.large,
body .gform_wrapper .right_label select.large {
  width: 70%;
  @include break(middle-bear) {
  	width: 100%;
  }
}

body .gform_wrapper .left_label li.gfield.gf_left_half,
body .gform_wrapper .right_label li.gfield.gf_left_half,
body .gform_wrapper .left_label li.gfield.gf_right_half,
body .gform_wrapper .right_label li.gfield.gf_right_half {
  @include break(middle-bear) {
    clear: none;
    width: 48%;
  }
  @include break(baby-bear) {
    clear: both;
    width: 100%;
  }
}

body .gform_wrapper .left_label li.gfield.gf_left_half,
body .gform_wrapper .right_label li.gfield.gf_left_half {
  @include break(middle-bear) {
    clear: left;
    float: left;
  }
}

body .gform_wrapper .left_label li.gfield.gf_right_half,
body .gform_wrapper .right_label li.gfield.gf_right_half {
  @include break(middle-bear) {
    clear: right;
    float: right;
  }
}


//
// Scroll text.
//

body .gform_wrapper li.gsection.gf_scroll_text {
  overflow-x: hidden;
  overflow-y: scroll;
  border: 1px solid $inputBorder !important;
  padding-right: 20px;
  border-radius: 4px;
}


//
// Base input sizing.
//

body .gform_wrapper .top_label input.large,
body .gform_wrapper .top_label select.large,
body .gform_wrapper .top_label textarea.textarea,
body .gform_wrapper .top_label li.gfield.gf_left_half input.medium,
body .gform_wrapper .top_label li.gfield.gf_left_half input.large,
body .gform_wrapper .top_label li.gfield.gf_left_half select.medium,
body .gform_wrapper .top_label li.gfield.gf_left_half select.large,
body .gform_wrapper .top_label li.gfield.gf_right_half input.medium,
body .gform_wrapper .top_label li.gfield.gf_right_half input.large,
body .gform_wrapper .top_label li.gfield.gf_right_half select.medium,
body .gform_wrapper .top_label li.gfield.gf_right_half select.large,
body .gform_wrapper .top_label li.gfield.gf_left_third input.medium,
body .gform_wrapper .top_label li.gfield.gf_left_third input.large,
body .gform_wrapper .top_label li.gfield.gf_left_third select.medium,
body .gform_wrapper .top_label li.gfield.gf_left_third select.large,
body .gform_wrapper .top_label li.gfield.gf_middle_third input.medium,
body .gform_wrapper .top_label li.gfield.gf_middle_third input.large,
body .gform_wrapper .top_label li.gfield.gf_middle_third select.medium,
body .gform_wrapper .top_label li.gfield.gf_middle_third select.large,
body .gform_wrapper .top_label li.gfield.gf_right_third input.medium,
body .gform_wrapper .top_label li.gfield.gf_right_third input.large,
body .gform_wrapper .top_label li.gfield.gf_right_third select.medium,
body .gform_wrapper .top_label li.gfield.gf_right_third select.large,
body .gform_wrapper .top_label li.gsection.gf_scroll_text,
body .gform_wrapper .ginput_complex .ginput_left input[type=text],
body .gform_wrapper .ginput_complex .ginput_left input[type=url],
body .gform_wrapper .ginput_complex .ginput_left input[type=email],
body .gform_wrapper .ginput_complex .ginput_left input[type=tel],
body .gform_wrapper .ginput_complex .ginput_left input[type=number],
body .gform_wrapper .ginput_complex .ginput_left input[type=password],
body .gform_wrapper .ginput_complex .ginput_left select,
body .gform_wrapper .ginput_complex .ginput_right input[type=text],
body .gform_wrapper .ginput_complex .ginput_right input[type=url],
body .gform_wrapper .ginput_complex .ginput_right input[type=email],
body .gform_wrapper .ginput_complex .ginput_right input[type=tel],
body .gform_wrapper .ginput_complex .ginput_right input[type=number],
body .gform_wrapper .ginput_complex .ginput_right input[type=password],
body .gform_wrapper .ginput_complex .ginput_right select,
body .gform_wrapper .ginput_complex .ginput_full input[type=text],
body .gform_wrapper .ginput_complex .ginput_full input[type=url],
body .gform_wrapper .ginput_complex .ginput_full input[type=email],
body .gform_wrapper .ginput_complex .ginput_full input[type=tel],
body .gform_wrapper .ginput_complex .ginput_full input[type=number],
body .gform_wrapper .ginput_complex .ginput_full input[type=password],
body .gform_wrapper .ginput_complex .ginput_full select,
body .gform_wrapper .gfield_error .ginput_complex .ginput_left input[type=text],
body .gform_wrapper .gfield_error .ginput_complex .ginput_left input[type=url],
body .gform_wrapper .gfield_error .ginput_complex .ginput_left input[type=email],
body .gform_wrapper .gfield_error .ginput_complex .ginput_left input[type=tel],
body .gform_wrapper .gfield_error .ginput_complex .ginput_left input[type=number],
body .gform_wrapper .gfield_error .ginput_complex .ginput_left input[type=password],
body .gform_wrapper .gfield_error .ginput_complex .ginput_left select,
body .gform_wrapper .gfield_error .ginput_complex .ginput_right input[type=text],
body .gform_wrapper .gfield_error .ginput_complex .ginput_right input[type=url],
body .gform_wrapper .gfield_error .ginput_complex .ginput_right input[type=email],
body .gform_wrapper .gfield_error .ginput_complex .ginput_right input[type=tel],
body .gform_wrapper .gfield_error .ginput_complex .ginput_right input[type=number],
body .gform_wrapper .gfield_error .ginput_complex .ginput_right input[type=password],
body .gform_wrapper .gfield_error .ginput_complex .ginput_right select {
  width: 100% !important;
}



// Alerts
// =============================================================================

body .gform_wrapper .top_label .gfield_error,
body .gform_wrapper .top_label .gfield_error .ginput_container {
  width: 100%;
  max-width: 100%;
}

body .gform_wrapper li.gfield.gfield_error.gfield_contains_required label.gfield_label {
  margin-top: 0;
}

body .gform_wrapper li.gfield.gfield_error,
body .gform_wrapper li.gfield.gfield_error.gfield_contains_required {
  border: 1px solid;
  padding: 6px 10px !important;
  border-color: $errorBorder;
  color: $errorText;
  background-color: $errorBackground;
}

body .gform_wrapper .validation_message {
  font-weight: inherit;
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       // =============================================================================
// SCSS/SITE/STACKS/INTEGRITY/_BUDDYPRESS.SCSS
// -----------------------------------------------------------------------------
// Contains styles for BuddyPress.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Base Styles
//   02. Members Styles
//   03. Notification Counts
//   04. Form: Standard
//   05. Form: What's New
//   06. Form: Search Groups, Members, Messages, and Blogs
//   07. Item Header
//   08. Navigation
//   09. List: All Items
//   10. List: Activity
//   11. List: Groups
//   11. List: Blogs
//   12. List Item: Header
//   13. List Item: Meta
//   14. BP Widget
//   15. Button Navigation
//   16. Stand Alone Buttons
//   17. Pagination
//   18. Tables
//   19. Admin Bar
// =============================================================================

// Base Styles
// =============================================================================

.buddypress {
  .entry-content {
    margin: 0;
  }
}

.x-navbar .x-nav > .x-menu-item-buddypress {
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



// Members Styles
// =============================================================================

.buddypress {

  //
  // Notifications <table>.
  //

  .notifications {
    .title,
    td.date {
      max-width: 100px;
      @include text-overflow();
    }

    th.date,
    td.actions {
      width: 1%;
      white-space: nowrap;
    }

    .date {
      @include break(middle-bear) {
        display: none;
      }
    }

    .actions {
      @include break(baby-bear) {
        display: none;
      }
    }
  }


  //
  // Notification settings <table>.
  //

  .notification-settings {
    .icon {
      width: 25px;
    }

    th.yes,
    th.no {
      width: 40px;
      white-space: nowrap;
    }

    .yes,
    .no {
      text-align: center;
    }
  }


  //
  // Messages <table>.
  //

  .messages-notices {
    margin: 0 0 $bpSpacingSm;

    .thread-from {
      position: relative;
      width: 125px;
      @include break(middle-bear) {
        display: none;
      }

      .activity {
        display: block;
        margin: 10px 0 0;
        @include font-size(0.9);
        opacity: 0.5;
      }
    }

    .thread-info {
      .thread-excerpt {
        display: block;
        margin: 10px 0 0;
      }
    }

    .thread-options {
      width: 1%;
      text-align: center;
      vertical-align: middle;
      white-space: nowrap;

      input {
        margin-top: 0;
      }

      a {
        display: block;
      }
    }
  }


  //
  // Messages.
  //

  #message-subject,
  #message-recipients {
    text-align: center;
  }

  #message-recipients {
    margin-bottom: $bpSpacingSm;
  }

  .message-box {
    margin: 0 0 $bpSpacingSm;
    border: $bpBorder;
    padding: $bpSpacingSm;
    @include box-shadow(#{$bpBoxShadowOuter});
  }

  .message-metadata {
    margin-bottom: $bpSpacingSm;
    height: 25px;
    @include font-size(1.2);
    line-height: 25px;
    @include text-overflow();

    img {
      margin-right: 10px;
    }

    .activity {
      margin-left: 5px;
      opacity: 0.5;
    }
  }


  //
  // Profile settings <table>.
  //

  .profile-settings {
    td.field-name {
      width: 1%;
      white-space: nowrap;
    }

    select {
      margin: 0;
      width: 100%;
    }
  }


  //
  // Messages filters.
  //

  .messages-options-nav {
    margin: 0;
    border: $bpBorder;
    pad