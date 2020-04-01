document.addEventListener('ffReady', function () {
    var redirectPath = window.ffRedirectPath || '/factfinder/result';
    var isSearchResultPage = function () {
        return window.location.href.indexOf(redirectPath) >= 0;
    };

    factfinder.communication.EventAggregator.addBeforeDispatchingCallback(function (event) {
        if (event.type === 'search' && !isSearchResultPage() && !event.searchImmediate && event.navigation !== 'true') {
            event.cancel();
            window.location.href = redirectPath + factfinder.common.dictToParameterString(event);
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
