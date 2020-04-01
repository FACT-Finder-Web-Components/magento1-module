document.addEventListener('ffReady', function () {
     const redirectPath = window.ffRedirectPath || '/factfinder/result';
     const isSearchResultPage = function () {
         return window.location.href.indexOf(redirectPath) >= 0;
     };

    factfinder.communication.EventAggregator.addBeforeDispatchingCallback(function (event) {
        if (event.type === 'search' && !isSearchResultPage() && !event.searchImmediate && event.navigation !== 'true') {
            event.cancel()
            window.location.href = redirectPath + factfinder.common.dictToParameterString(event);
        }
    });
});

document.addEventListener('dom-updated', function () {
    const parentCategory = document.getElementsByClassName('ff-suggest-parent-category');
    Array.prototype.forEach.call(parentCategory, function (el) {
        if (el.innerText === '>') {
            el.style.display = 'none';
        } else {
            el.style.display = 'inline-block';
        }
    });
});
