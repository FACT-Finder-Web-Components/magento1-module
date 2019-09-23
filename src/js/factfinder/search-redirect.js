document.addEventListener('ffReady', function () {
    var redirectPath = window.ffRedirectPath || '/factfinder/result';

    factfinder.communication.FFCommunicationEventAggregator.addBeforeDispatchingCallback(function (event) {
        if (event.type === 'search' && !event.__immediate) {
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
