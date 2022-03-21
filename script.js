(function($){

    let productId;
    let variantImgs;

    function loadImage(){

        variantImgs.each(function(){

            let self;
            self = $(this);

            let variantTitle;
            variantTitle = $(this).parent().attr("title");

            if(productParentId == 0){
                
                $.ajax({ url: "***/queries.php", type: "POST", data: {
                        variant: {
                            title: variantTitle,
                            productId: productId
                        }
                    }, success: function(response){
                        
                        response = JSON.parse(response)[0]["image"];
                        self.attr("src", response).attr("data-src", response);

                    }
                });
                
            }
            else{

                $.ajax({ url: "***/queries.php", type: "POST", data: {
                        variant: {
                            title: variantTitle,
                            productId: productParentId
                        }
                    }, success: function(response){

                        response = JSON.parse(response)[0]["image"];
                        console.log(response);
                        console.log(self);
                        self.attr("src", response).attr("data-src", response);

                    }
                });

            }

        });

    }

    String.prototype.turkishtoEnglish = function () {
        return this.replace(/Ğ/gim, "g")
		.replace(/Ü/gim, "u")
		.replace(/Ş/gim, "s")
		.replace(/I/gim, "i")
		.replace(/İ/gim, "i")
		.replace(/Ö/gim, "o")
		.replace(/Ç/gim, "c")
		.replace(/ğ/gim, "g")
		.replace(/ü/gim, "u")
		.replace(/ş/gim, "s")
		.replace(/ı/gim, "i")
		.replace(/ö/gim, "o")
		.replace(/ç/gim, "c");
    };

    $(document).ready(function(){

        
        productId = $(".product-right a[data-selector='add-to-cart']").attr("data-product-id");
        variantImgs = $(".product-right .product-options .variant-list-group[data-group-id='1'] img");

        loadImage();

        let targetVariant;
        targetVariant = $(".product-right .product-options-content").find(".variant-list-group[data-group-id='2'] span");

        setTimeout(function(){

            for (let index = 0; index < targetVariant.length; index++) {

                let targetSpan;
                targetSpan = $(".product-right .product-options-content").find(".variant-list-group[data-group-id='2'] span:eq(" + index + ")");

                if(targetSpan.hasClass("variant-no-stock") === false){

                    targetSpan.trigger("click");
                    break;

                }
                
            }

        }, 500);
        
    });

    $(document).on("DOMNodeRemoved", ".loading-bar", function() {

        productParentId = parseInt(pageParams.product.parentId);
        variantImgs = $(".product-right .product-options .variant-list-group[data-group-id='1'] img");

        loadImage();

    });

    $(document).on("mouseenter", ".product-right .product-options .variant-list-group[data-group-id='1'] span", function(){

        $("#secondaryImage").css({
            "background": "url('" + $(this).find("img").attr("src") + "')"
        }).removeClass("d-none");

    });

    $(document).on("mouseleave", ".product-right .product-options .variant-list-group[data-group-id='1'] span", function(){
        
        $("#secondaryImage").css({
            "background": "url('" + $(this).parent().find(".variant-selected").find("img").attr("src") + "')"
        });

    });

    $(document).on("click", ".product-right .product-options .variant-list-group[data-group-id='1'] span", function(){
        
        let self;
        self = $(this);

        let targetVariant;
        targetVariant = self.parents(".variant-plural").find(".variant-list-group[data-group-id='2'] span");

        setTimeout(function(){

            for (let index = 0; index < targetVariant.length; index++) {

                let targetSpan;
                targetSpan = self.parents(".variant-plural").find(".variant-list-group[data-group-id='2'] span:eq(" + index + ")");

                if(targetSpan.hasClass("variant-no-stock") === false){

                    targetSpan.trigger("click");
                    break;

                }
                
            }

        }, 500);

    });

})(jQuery);
