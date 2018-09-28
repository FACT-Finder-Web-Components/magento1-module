document.addEventListener("WebComponentsReady", function () {
    factfinder.communication.FFCommunicationEventAggregator.addBeforeDispatchingCallback(function (event) {
        if (event.type === "search" && window.location.href.match(/catalogsearch\/result/) === null) {
            delete event.type;
            delete event.version;
            var params = factfinder.common.dictToParameterString(event);
            window.location.href = "/catalogsearch/result/" + params;
            delete event.type;
        }
    });
});

document.addEventListener("dom-updated", function(){
    var parentCategory = document.getElementsByClassName('ff-suggest-parent-category');
    Array.prototype.forEach.call(parentCategory, function(el) {
        if (el.innerText == '>') {
            el.style.display = "none";
        } else {
            el.style.display = "inline-block";
        }
    });
});