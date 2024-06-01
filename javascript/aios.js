let webdomain = window.location.hostname;
const options = {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
    },
    body: JSON.stringify({domain: webdomain}),
};

fetch('https://ada.skynettechnologies.us/check-website',options)
.then((response) => response.json())
.then(function(response) {
    var iframeHtml = '';
    if (response.status == 3) {
        iframeHtml += '<div id="cancelDiv">';
        iframeHtml +='<h5 style="color: #aa1111">It appears that you have already registered! Please click on the "Manage Subscription" button to renew your subscription.<br> Once your plan is renewed, please refresh the page.</h5>';
        iframeHtml +='<div style="text-align: left; width:100%; padding-bottom: 10px;"><a target="_blank" class="aioa-cancel-button"  href="'+ response.settinglink +'">Manage Subscription</a></div>';
        iframeHtml +='</div>';   
        } else if (response.status > 0 && response.status < 3) {
        iframeHtml +='<div id="activeDiv">';
        iframeHtml +='<div style="width:80%; margin-top: 50px; padding-bottom: 10px;display:flex;justify-content:space-between;">';
        iframeHtml +='<h3  style=" width: 50%;">Widget Preferences:</h3>';
        iframeHtml +='<div><a target="_blank" class="aioa-cancel-button"  href="'+ response.manage_domain +'">Manage Subscription</a></div>';
        iframeHtml +='</div>';
        iframeHtml +='<iframe id="aioamyIframe" width="80%" style="max-width: 1920px; border: none;" height="1100px"  src="'+ response.settinglink +'"></iframe>';
        iframeHtml +='</div>';
        } else {
        iframeHtml +='<div id="regDiv">';
                iframeHtml +='<iframe src="https://ada.skynettechnologies.us/trial-subscription?isframe=true&website='+ webdomain +'&platform=typo3&utm_source=typo3-extension&utm_medium=create-account&utm_campaign=trial-subscription" height="1100px;" width="80%" style="border: none;"></iframe>';
                iframeHtml +='</div>';
        }

    document.getElementById("allinoneaccessibility_form").innerHTML = iframeHtml;

})
.catch((error) => {
    // Code for handling the error
    console.log(error);
});