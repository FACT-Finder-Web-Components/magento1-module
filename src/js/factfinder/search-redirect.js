document.addEventListener("WebComponentsReady", function () {
    factfinder.communication.FFCommunicationEventAggregator.addBeforeDispatchingCallback(function (event) {
        if (event.type === "search" && window.location.href.match(/catalogsearch\/result/) === null) {
            let params = Object.assign({}, event);
            delete params.type;
            delete params.version;
            window.location.href = "/catalogsearch/result/" + factfinder.common.dictToParameterString(params);
        }
    });
});

document.addEventListener("dom-updated", function () {
    var parentCategory = document.getElementsByClassName('ff-suggest-parent-category');
    Array.prototype.forEach.call(parentCategory, function (el) {
        if (el.innerText == '>') {
            el.style.display = "none";
        } else {
            el.style.display = "inline-block";
        }
    });
});

document.addEventListener(`ffReady`, () => {
    factfinder.communication.ResultDispatcher.subscribe(`navigation`, (navData, e) => {
        if (window.location.href.match(/catalogsearch\/result/) === null) {
            navData.forEach(navSection => navSection.forEach(navEl => {
                    let url = navEl.__TARGET_URL__.url.split('?');
                    url.splice(1, 0, 'catalogsearch/result?');
                    navEl.__TARGET_URL__.setUrl(url.join(''));
                }
            ))
        }
    });
});