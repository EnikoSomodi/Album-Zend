function Notifier(notificationCt) {
  /**
   * @prop {DOMElement}
   */
  this.notificationCt = notificationCt;

  this.info = function(message) {
    $('.alertBox')
        .html('<i class="fas fa-exclamation-circle"></i> '.concat(message))
        .removeClass('success error loading')
        .addClass('info')
        .fadeIn();

     setTimeout(function() {
       $('.alertBox').fadeOut();
     }, 3000);
  };

  this.loading = function(message) {
    $('.alertBox')
        .html('<i class="fas fa-spinner fa-spin"></i> '.concat(message))
        .removeClass('success fail info')
        .addClass('loading')
        .fadeIn();
  };

  this.success = function(message) {
    $('.alertBox')
        .html('<i class="fas fa-check-circle"></i> '.concat(message))
        .removeClass('fail loading')
        .addClass('success')
        .fadeIn();

    setTimeout(function() {
        $('.alertBox').fadeOut();
    }, 3000);
  };

  this.error = function(message) {
    $('.alertBox')
        .html('<i class="fas fa-times"></i> '.concat(message))
        .removeClass('success loading')
        .addClass('fail')
        .fadeIn();

    setTimeout(function() {
        $('.alertBox').fadeOut();
    }, 3000);
  };
}
