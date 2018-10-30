var AuctionProposal = function () {
    function proposalInit() {
        $('#close').click(function () {
            parent.location.reload();
        });
    }

    function init() {
        proposalInit();
    }

    return {
        init: init
    }
}();

jQuery(document).ready(function () {
    AuctionProposal.init()
});