(function($) {
    $(document).ready(function () {
        var $formFieldSlider =  $( ".ds-vc-slider" );
        $formFieldSlider.each(function () {
            var $sliderItem = $(this),
                $inputTextSlider = $sliderItem.next('input[type="text"]'); // $inputTextValueSlider - input next to slider with value of slider
            $sliderItem.slider({
                range: "max",
                min: $sliderItem.data("min"),
                max: $sliderItem.data("max"),
                value: $sliderItem.data("default"),
                step: $sliderItem.data("step"),
                slide: function( event, ui ) {
                    $inputTextSlider.val( ui.value );
                }
            });
            $inputTextSlider.val( $sliderItem.slider( "value" ) );
        });
    });
})(jQuery); // Fully reference jQuery after this point.