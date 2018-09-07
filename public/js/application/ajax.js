function Ajax() {
  this.init = function() {
    return this;
  };

  this.post = function(url, data, successCallback, errorCallback) {
    $.ajax(url, {
      type: 'post',
      dataType: 'json',
      data: data,
      success: successCallback,
      error: errorCallback
    });
  }

  // Initialize
  this.init();
}
