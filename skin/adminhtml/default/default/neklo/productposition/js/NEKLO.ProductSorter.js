(function (container, document, _) {
  container.NEKLO = container.NEKLO || {};

  /**
   * @class
   * @param setups
   * @param options
   * @param parent This is ul list element include all instances from this class
   * @constructor
   */
  function Element (setups, options, parent, onImageLoaded) {
    this.setups = setups;
    this.parent = parent;
    var element = document.createElement(setups.selector);
    var tmp = _.template(setups.template)(options);

    var image = new Image();
    image.src = options.image;
    image.onload = onImageLoaded;
    image.onerror = onImageLoaded;

    element.className = setups.className;
    element.style.width = setups.width;
    element.style.marginLeft = setups.margin;
    element.style.marginBottom = setups.margin;
    element.innerHTML = tmp;
    element.dataset.position = options.position;
    element.dataset.entity_id = options.entity_id;
    element.dataset.attached = options.attached === true || options.attached === 'true' ? true : false;
    element.dataset.isDirty = options.isDirty === true || options.isDirty === 'true' ? true : false;

    element.addEventListener('click', this.eventClick, false);
    addEvent(element, 'dragstart', this.eventDragStart);
    addEvent(element, 'dragenter', this.eventDragEnter);
    addEvent(element, 'dragover', this.eventDragOver);
    addEvent(element, 'drop', this.eventDrop);
    addEvent(element, 'dragend', this.eventDragEnd);

    element.$$instance = this;
    element.$$instance.getElement = function () {
      return element;
    };

    return element;
  }

  Element.prototype = {
    getParentInstance: function () {
      return this.getElement().$$instance.parent.$$instance;
    },
    isAttached: function () {
      var element = this.getElement();
      var attached = (element.dataset.attached === 'true') ? true : false;
      return attached;
    },
    searchElementSibling: function (element, prefix) {
      if (!element) {
        return
      }
      if (element.dataset.attached === 'true' || !element.classList.contains(this.setups.className)) {
        if (prefix === 'next') {
          return this.searchElementSibling(element.nextSibling);
        } else {
          return this.searchElementSibling(element.previousSibling);
        }
      }
      return element;
    },
    updateElementState: function (options, height) {
      var element = this.getElement();
      var states = this.setups.states;
      element.style.height = height + 'px';
      element.dataset.position = options.position;
      element.dataset.entity_id = options.entity_id;
      element.dataset.attached = options.attached === true || options.attached === 'true' ? true : false;
      element.dataset.isDirty = options.isDirty === true || options.isDirty === 'true' ? true : false;

      if (options.attached === true || options.attached === 'true') {
        element.classList.add(states.attached);
        element.setAttribute('draggable', false);
      } else {
        element.classList.remove(states.attached);
        element.setAttribute('draggable', true);
      }

      var leftSibling = this.searchElementSibling(element.previousSibling, 'previous');
      var rightSibling = this.searchElementSibling(element.nextSibling, 'next');

      if (!leftSibling) {
        element.classList.add(states.disableLeft);
      } else {
        element.classList.remove(states.disableLeft);
      }

      if (!rightSibling) {
        element.classList.add(states.disableRight);
      } else {
        element.classList.remove(states.disableRight);
      }
    },
    eventClick: function (e) {
      if (e.preventDefault) {
        e.preventDefault();
      }
      var target = e.target;
      var attr = target.getAttribute('btn') || target.parentNode.getAttribute('btn');

      if (attr === 'attached') {
        (this.$$instance.handlerAttach.bind(this))();
      }
      if (attr === 'toLeft') {
        (this.$$instance.handlerToLeft.bind(this))();
      }
      if (attr === 'toBegin') {
        (this.$$instance.handlerToBegin.bind(this))();
      }
      if (attr === 'toRight') {
        (this.$$instance.handlerToRight.bind(this))();
      }
      if (attr === 'toEnd') {
        (this.$$instance.handlerToEnd.bind(this))();
      }

      return false;
    },
    eventDragStart: function (e) {
      var parent = this.$$instance.getParentInstance();
      parent.setDragElement(this);
      e.dataTransfer.effectAllowed = 'move';
      e.dataTransfer.setData('text/html', this.innerHTML);
      this.style.opacity = '0.5';
    },
    eventDragOver: function (e) {
      if (e.preventDefault) { e.preventDefault(); }
      e.dataTransfer.dropEffect = 'move';
      return false;
    },
    eventDragEnter: function (e) {
      if (e.preventDefault) { e.preventDefault(); }
      var parent = this.$$instance.getParentInstance();
      var items = parent.getListElements();
      var states = this.$$instance.setups.states;
      [].forEach.call(items, function (item) {
        item.classList.remove(states.over);
      });
      this.classList.add(states.over);
      return false;
    },
    eventDrop: function (e) {
      if (e.preventDefault) { e.preventDefault(); }
      if (e.stopPropagation) { e.stopPropagation(); }
      var parent = this.$$instance.getParentInstance();
      var dragElement = parent.getDragElement();
      var targetElement = this;
      this.$$instance.handlerMoving(dragElement, targetElement);

      return false;
    },
    eventDragEnd: function (e) {
      var parent = this.$$instance.getParentInstance();
      var items = parent.getListElements();
      var states = this.$$instance.setups.states;
      [].forEach.call(items, function (item) {
        item.classList.remove(states.over);
      });
      this.style.opacity = '1';
    },
    handlerToLeft: function () {
      var parent = this.$$instance.getParentInstance();
      var targetElement = this.$$instance.searchElementSibling(this.previousSibling, 'previous');
      if (!targetElement) {
        return;
      }
      var begin = +targetElement.dataset.position;
      var end = +this.dataset.position;

      parent.sortElements(begin, end, this, false);
    },
    handlerToBegin: function () {
      var parent = this.$$instance.getParentInstance();
      var targetElement = _.find(parent.getListElements(), 'dataset.attached', 'false');
      if (!targetElement || targetElement === this) {
        return;
      }
      var begin = +targetElement.dataset.position;
      var end = +this.dataset.position;

      parent.sortElements(begin, end, this, false);
    },
    handlerToRight: function () {
      var parent = this.$$instance.getParentInstance();
      var targetElement = this.$$instance.searchElementSibling(this.nextSibling, 'next');
      if (!targetElement) {
        return;
      }
      var begin = +this.dataset.position;
      var end = +targetElement.dataset.position;

      parent.sortElements(begin, end, this, true);
    },
    handlerToEnd: function () {
      var parent = this.$$instance.getParentInstance();
      var items = _.slice(parent.getListElements()).reverse();
      var targetElement = _.find(items, 'dataset.attached', 'false');
      if (!targetElement  || targetElement === this) {
        return;
      }
      var begin = +this.dataset.position;
      var end = +targetElement.dataset.position;

      parent.sortElements(begin, end, this, true);
    },
    handlerMoving: function (dragElement, targetElement) {
      if (dragElement == targetElement || targetElement.$$instance.isAttached()) {
        return false;
      }

      var parent = dragElement.$$instance.getParentInstance();
      var begin, end, reverse;

      if (+dragElement.dataset.position > +targetElement.dataset.position) {
        begin = +targetElement.dataset.position;
        end = +dragElement.dataset.position;
        reverse = false;
      } else {
        begin = +dragElement.dataset.position;
        end = +targetElement.dataset.position;
        reverse = true;
      }

      parent.sortElements(begin, end, dragElement, reverse);
    },
    handlerAttach: function () {
      var parent = this.$$instance.getParentInstance();
      this.dataset.isDirty = true;
      this.dataset.attached = (this.dataset.attached === 'true' || this.dataset.attached === true) ? false : true;
      parent.updateElementsState();
      parent.onListUpdated([this]);
    }
  };

  /**
   * @class
   * @param setups
   * @constructor
   */
  function List (setups) {
    this.options = setups;
    this.separatorElements = [];
    this.dragElement = null;
    var element = document.createElement(setups.list.selector);
    element.className = setups.list.className;
    element.style.marginLeft = setups.list.margin;
    element.style.width = setups.grid.cols * (parseInt(setups.item.width) + parseInt(setups.item.margin)) + 'px';
    element.$$instance = this;
    element.$$instance.getListElement = function () {
      return element;
    };

    return element;
  }

  List.prototype = {
    getOptions: function () {
      return this.options;
    },
    getListElements: function () {
      return this.getListElement().childNodes;
    },
    getDragElement: function () {
      return this.dragElement;
    },
    setDragElement: function (value) {
      this.dragElement = value;
    },
    addSeparators: function () {
      var options = this.getOptions();
      var setups = options.separator;
      var items = this.getListElements();
      var parent = this.getListElement();
      var length = items.length;
      var sizeOfPage = options.grid.cols * options.grid.rows;
      var insertPoints = [];

      var template = setups.template;

      for (var i = 0; i < length; i++) {
        if (i % sizeOfPage === 0 || i === 0) {
          insertPoints.push(items[i]);
        }
      }

      _.forEach(insertPoints, function (element, key) {
        key++;
        var separator = document.createElement(setups.selector);
        separator.className = setups.className;
        separator.innerHTML = _.template(template)({value: key});

        parent.insertBefore(separator, element);
        parent.$$instance.separatorElements.push(separator);
      });
    },
    removeSeparators: function () {
      var parent = this.getListElement();
      _.forEach(parent.$$instance.separatorElements, function (separator) {
        parent.removeChild(separator);
      });
      parent.$$instance.separatorElements = [];
    },
    sortElements: function (begin, end, dragElement, reverse) {
      var self = this;
      var beginForItems = begin - 1;
      var endForItems = end - 1;
      this.removeSeparators();
      var position;
      var items = this.getListElements();
      items = _.slice(items, beginForItems, endForItems + 1);
      var oldItems = _.clone(items, true);

      if (reverse) {
        position = end;
        items = items.reverse();
      } else {
        position = begin;
      }

      items = this.sortElementsByOrder(items, dragElement, position);
      //setTimeout(function () {
      self.sortElementsInDOM(items, oldItems);
      self.updateElementsState();
      self.addSeparators();
      self.onListUpdated(items);
      //}, 10);
    },
    sortElementsByOrder: function (array, dragElement, position) {
      var needSortedElement;
      _.forEach(array, function (item) {
        if (item.$$instance.isAttached()) {
          return;
        }
        item.dataset.isDirty = true;
        if (needSortedElement) {
          needSortedElement.dataset.position = item.dataset.position;
        }

        needSortedElement = item;

        if (item.dataset.entity_id === dragElement.dataset.entity_id) {
          item.dataset.position = position;
        }
      });

      array.sort(function (a, b) {
        return (+a.dataset.position) - (+b.dataset.position)
      });
      return array;
    },
    sortElementsInDOM: function (sortedElements, currentElements) {
      var parent = this.getListElement();
      var insertPoint = document.createElement('li');
      parent.insertBefore(insertPoint, currentElements[0]);

      _.forEach(currentElements, function (current) {
        parent.removeChild(current);
      });

      _.forEach(sortedElements, function (sorted) {
        parent.insertBefore(sorted, insertPoint);
      });

      parent.removeChild(insertPoint);
    },
    updateList: function (data, setups) {
      if (!data || (!Array.isArray(data))) {
        throw new Error('You can update list of elements with pass data like array');
      }
      if (!data.length) { return; }
      var list = this.createListItems(data, setups);
      return list;
    },
    createListItems: function (data, setups) {
      var self = this;
      var i = 0;
      var length = data.length;
      var list = this.getListElement();
      var imgCount = 0;

      for (i; i < length; i++) {
        var element = new Element (setups, data[i], list, onImageLoaded);
        list.appendChild(element);
      }

      function onImageLoaded () {
        ++imgCount;
        if (imgCount === length) { self.updateElementsState(); }
      };

      return list;
    },
    updateElementsState: function () {
      var separator = this.getOptions().separator;
      var elements = this.getListElements();
      var height = 0;
      _.forEach(elements, function (o) {
        if (o.clientHeight > height) {height = o.clientHeight}
      });
      _.forEach(elements, function (element) {
        if (element.classList.contains(separator.className)) { return; }
        element.$$instance.updateElementState(element.dataset, height);
      });
    }
  };

  /**
   * @class
   * @param options
   * @constructor
   */
  function Button (options) {
    this.options = options;
    var setups = options.uploadButton;
    var button = document.createElement(setups.selector);
    button.className = setups.className;
    button.innerHTML = setups.label;
    button.$$instance = this;
    button.$$instance.getElement = function () {
      return button;
    };

    button.addEventListener('click', this.uploadPage, false);

    return button;
  }

  Button.prototype = {
    uploadPage: function (e) {
      e.preventDefault();
      this.$$instance.onUploadPage();
    },
    updateState: function (pageNumber, totalItemsLength) {
      var options = this.options;
      var cols = options.grid.cols;
      var rows = options.grid.rows;
      var currentItemsLength = pageNumber * rows * cols;

      if (currentItemsLength >= totalItemsLength) {
        var button = this.getElement();
        button.style.display = 'none';
      }
    },
    updateWidth: function (list) {
      var button = this.getElement();
      button.style.width = parseInt(list.style.width) + parseInt(list.style.marginLeft) + 'px';
    }
  };


  /**
   * @class
   * @extend SimpleEventEmitter
   * @constructor
   */
  function ProductSorter(options) {
    options = options || {};
    var self = this;
    this.dataStoring = [];
    this.totalItems = options.totalItems;
    this.page = 1;
    delete options.totalItems;
    this.options = _.merge(this.options, options);

    var count = +this.options.grid.cols * +this.options.grid.rows;
    this.options.connector.$getPage(this.page, count, function (data) {
      var options = self.options;
      var targetDOM = options.targetDOM;

      if (!data.length) {
        return ProductSorter.createErrorElement(options);
      }

      var list = new List(options);
      var button = new Button(options);

      targetDOM.appendChild(list);
      targetDOM.appendChild(button);

      list.$$instance.updateList(data, options.item);
      list.$$instance.addSeparators();

      button.$$instance.updateState(self.page, self.totalItems);
      button.$$instance.updateWidth(list);

      targetDOM.$$instance = self;

      button.$$instance.onUploadPage = self.uploadPage.bind(self);
      self.onUploadedPage = function (data) {
        list.$$instance.removeSeparators();
        list.$$instance.updateList(data, options.item);
        list.$$instance.addSeparators();
        button.$$instance.updateState(self.page, self.totalItems);
      };
      list.$$instance.onListUpdated = self.updateDataStoring.bind(self);
    }, function (error) {
      ProductSorter.createErrorElement(options, error);
    });

    return this;
  }

  ProductSorter.createErrorElement = function (options, message) {
    var parent = options.targetDOM;
    var setups = options.error;
    var element = document.createElement(setups.selector);
    element.className = setups.className;
    element.innerHTML = message || setups.message;
    parent.appendChild(element);
  };

  ProductSorter.EVENTS = {
    ON_ITEMS_CHANGED: 'ProductSorter:ON_ITEMS_CHANGED'
  };

  ProductSorter.prototype = {
    EVENTS: ProductSorter.EVENTS,
    options: {
      targetDOM: document.querySelector('body'),
      grid: {
        cols: 5,
        rows: 2
      },
      uploadButton: {
        label: 'Upload Next Page',
        className: 'nd-btn upload',
        selector: 'button'
      },
      error: {
        className: 'nd-list__error',
        selector: 'div',
        message: 'Empty data'
      },
      list: {
        className: 'nd-list',
        selector: 'ul',
        margin: '-20px'
      },
      item: {
        className: 'nd-list__item',
        selector: 'li',
        width: '166px',
        margin: '20px',
        template: '<span>Say Hello</span>',
        states: {
          attached: 'attached',
          disableLeft: 'disableLeft',
          disableRight: 'disableRight',
          over: 'over'
        }
      },
      separator: {
        className: 'nd-list__separator',
        selector: 'li',
        template: ''
      }
    },
    getDataStoring: function () {
      return this.dataStoring;
    },
    updateDataStoring: function (elements) {
      var store = this.getDataStoring();
      var recentlyUpdated = [];

      _.forEach(elements, function (element) {
        if (element.dataset.isDirty !== 'true') {
          return;
        }
        var item = element.dataset;
        var replacedItem = _.find(store, 'entity_id', item.entity_id);
        if (replacedItem) {
          replacedItem = item;
        } else {
          store.push(item);
        }
        recentlyUpdated.push(item);
      });

      if (this.emit) {
        this.emit(this.EVENTS.ON_ITEMS_CHANGED, recentlyUpdated);
      }
    },
    uploadPage: function () {
      var options = this.options;
      var cols = options.grid.cols;
      var rows = options.grid.rows;
      var count = +cols * +rows;
      options.connector.$getPage(++this.page, count, this.onUploadedPage);
    },
    doSave: function () {
      var data = this.getDataStoring();
      var connector = this.options.connector;
      return connector.$save(data);
    }
  };

  container.NEKLO.ProductSorter = container.NEKLO.SimpleEventEmitter ? container.NEKLO.SimpleEventEmitter.mixin(ProductSorter) : container.NEKLO.ProductSorter;
})(window, document, _);