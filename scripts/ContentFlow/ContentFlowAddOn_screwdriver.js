/*  ContentFlowAddOn_screwdriver, version 1.0 
 *  (c) 2008 - 2010 Sebastian Kutsch
 *  <http://www.jacksasylum.eu/ContentFlow/>
 *
 *  This file is distributed under the terms of the MIT license.
 *  (see http://www.jacksasylum.eu/ContentFlow/LICENSE)
 */

new ContentFlowAddOn ('screwdriver', {

    conf: {
        angle: 120, // rotation angle in degree
        curvature: 1.0 // scalefactor of curvature 
    },

    init: function() {
    },
    
    onloadInit: function (flow) {
    },
	
	ContentFlowConf: {
        scaleFactorLandscape: "max",      // scale factor of landscape images ('max' := height= maxItemHeight)
        endOpacity: 0.1,                  // opacity of last visible item on both sides
        /* ==================== actions ==================== */

        onDrawItem: (function (item) {
                if (ContentFlowGlobal.Browser.IE) {
                    return function (item) {
                        var ac = this.getAddOnConf("screwdriver");
                        var rP = item.relativePosition;
                        var rPN = item.relativePositionNormed;
                        var angle = ac.angle*item.relativePositionNormed ;
                        angle *= Math.PI/180;
                        if (ContentFlowGlobal.Browser.IE6) angle /= 100;

                        // rotate
                        m11 = Math.cos( angle);
                        m12 = -Math.sin( angle);
                        m21 = Math.sin( angle);
                        m22 = Math.cos( angle);

                        //shear
                        m12 += Math.sin(angle/2);

                        item.element.style.filter += " progid:DXImageTransform.Microsoft.Matrix(M11="+m11+" M12="+m12+" M21="+m21+" M22="+m22+" SizingMethod='auto expand')";
                    }
                }
                else {
                    //if (window.opera) return new Function ();
                    return function (item) {
                        var ac = this.getAddOnConf("screwdriver");
                        var el = item.element;
                        var angle = ac.angle*item.relativePositionNormed ;
                        var sty = "rotate("+angle+"deg) skewx("+angle/2+"deg)";
                        el.style.MozTransform = sty;
                        el.style.webkitTransform = sty;
                        el.style.transform = sty;
                    };
                }
            })()
        ,

        /* ==================== calculations ==================== */

        calcCoordinates: function (item) {
            var ac = this.getAddOnConf("screwdriver");
            var rP = item.relativePosition;
            var rPN = item.relativePositionNormed;
            var vI = rPN != 0 ? rP/rPN : 0 ; // visible Items

            var f = 1 - 1/Math.exp( Math.abs(rP)*0.75);
            var x =  item.side * vI/(vI+1)* f; 
            y = 1 + Math.sin(Math.PI/10*Math.abs(rPN))*ac.curvature*(ac.curvature != 1 ? 4 : 1);

            return {x: x, y: y};
        }
        
	
    }

});
