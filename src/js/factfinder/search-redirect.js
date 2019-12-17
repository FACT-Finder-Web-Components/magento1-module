document.addEventListener('ffReady', function () {
    var redirectPath = window.ffRedirectPath || '/factfinder/result';

    factfinder.communication.EventAggregator.addBeforeDispatchingCallback(function (event) {
        if (event.type === 'search' && !event.__immediate) {
            delete event.type;
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
