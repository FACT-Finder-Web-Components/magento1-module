document.addEventListener('ffReady', function () {
    var redirectPath = window.ffRedirectPath || '/factfinder/result';
    factfinder.communication.FFCommunicationEventAggregator.addBeforeDispatchingCallback(function (event) {
        if (event.type === 'search' && window.location.href.indexOf(redirectPath) < 0) {
            var params = Object.assign({}, event);
            delete params.type;
            delete params.version;
            window.location.href = redirectPath + factfinder.common.dictToParameterString(params);
        }
    });
});

document.addEventListener('dom-updated', function () {
    var parentCategory = document.getElementsByClassName('ff-suggest-parent-category');
    Array.prototype.forEach.call(parentCategory, function (el) {
        if (el.innerText === '>') {
            el.style.display = 'none';
        } else {
            el.style.display = 'inline-block';
        }
    });
});

document.addEventListener('ffReady', function () {
    factfinder.communication.ResultDispatcher.subscribe('navigation', function (navData) {
        var redirectPath = window.ffRedirectPath || '/factfinder/result';
        if (window.location.href.indexOf(redirectPath) < 0) {
            navData.forEach(function (navSection) {
                navSection.forEach(function (navEl) {
                    var url = navEl.__TARGET_URL__.url.split('?')[1];
                    navEl.__TARGET_URL__.setUrl(redirectPath + '?' + url);
                });
            });
        }
    });
});
