(function (container, Ajax){
  container.NEKLO = container.NEKLO || {};
  container.NEKLO.Connectors = {};

  /**
   * @class
   * @param options
   * @constructor
   */
  function Magento1Connector(options) {
    this.options = options || {};
  }

  Magento1Connector.prototype = {
    $getPage: function (pageCount, count, success, error) {
      var self = this;
      return new Ajax.Request(this.options.url, {
        parameters: {
          page: pageCount,
          count: count
        },
        onSuccess: function (response) {
          var data = self.$parseResponse(response);
          return success && typeof success === 'function' ? success(data) : data;
        },
        onFailure: function (err) {
          return error && typeof error === 'function' ? error(err) : err;
        }
      });
    },

    $parseResponse: function (res) {
      return res.responseJSON;
    },

    $save: function (data) {
      return data;
    }
  };

  /**
   * @class
   * @param options
   * @constructor
   */
  function FakeConnector (options) {
    this.options = options || {};
  }

  FakeConnector.prototype = {
    $getPage: function (pageCount, count, success, error) {
      var data = [];
      var i = (pageCount - 1) * count + 1;
      var length = pageCount * count + 1;
      for (i; i < length; i++) {
        data.push({
          entity_id: i,
          position: i,
          attached: false,
          name: 'Name ' + i,
          sku: 'sku ' + i,
          price: '$' + i,
          image: ''
        });
      }

      setTimeout(success(data), 1000);
    },
    $save: function (data) {
      return data;
    }
  };

  container.NEKLO.Connectors.Magento1Connector = Magento1Connector;
  container.NEKLO.Connectors.FakeConnector = FakeConnector;
})(window, Ajax);