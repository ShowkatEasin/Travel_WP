(()=>{"use strict";var e={n:t=>{var a=t&&t.__esModule?()=>t.default:()=>t;return e.d(a,{a}),a},d:(t,a)=>{for(var n in a)e.o(a,n)&&!e.o(t,n)&&Object.defineProperty(t,n,{enumerable:!0,get:a[n]})},o:(e,t)=>Object.prototype.hasOwnProperty.call(e,t)};const t=window.wp.element,a=window.wp.blocks,n=window.wp.blockEditor,o=window.wp.components,r=window.wp.i18n,l=window.wp.serverSideRender;var i=e.n(l);(0,a.registerBlockType)("yatra/destination",{apiVersion:2,title:(0,r.__)("Destination","yatra"),description:(0,r.__)("This block is used to show the destination list of Yatra WordPress plugin.","yatra"),icon:{foreground:"#1abc9c",src:"dashicons dashicons-location"},category:"yatra",edit:e=>{const{attributes:a,setAttributes:l}=e,s=(0,n.useBlockProps)();return(0,t.createElement)("div",s,(0,t.createElement)(i(),{block:"yatra/destination",attributes:a}),(0,t.createElement)(n.InspectorControls,{key:"setting"},(0,t.createElement)("div",{id:"yatra-destination-controls"},(0,t.createElement)(o.Panel,null,(0,t.createElement)(o.PanelBody,{title:(0,r.__)("Destination Settings","yatra"),initialOpen:!0},(0,t.createElement)(o.SelectControl,{label:(0,r.__)("Order","yatra"),value:a.order,options:[{label:(0,r.__)("Ascending","yatra"),value:"asc"},{label:(0,r.__)("Descending","yatra"),value:"desc"}],onChange:e=>l({order:e})}),(0,t.createElement)(o.SelectControl,{label:(0,r.__)("Columns","yatra"),value:a.columns,options:[{label:(0,r.__)("Two (2)","yatra"),value:2},{label:(0,r.__)("Three (3)","yatra"),value:3},{label:(0,r.__)("Four (4)","yatra"),value:4}],onChange:e=>l({columns:e})}))))))}})})();