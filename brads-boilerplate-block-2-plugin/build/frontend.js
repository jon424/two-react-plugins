(()=>{"use strict";var e={n:t=>{var r=t&&t.__esModule?()=>t.default:()=>t;return e.d(r,{a:r}),r},d:(t,r)=>{for(var o in r)e.o(r,o)&&!e.o(t,o)&&Object.defineProperty(t,o,{enumerable:!0,get:r[o]})},o:(e,t)=>Object.prototype.hasOwnProperty.call(e,t)};const t=window.wp.element,r=window.React,o=window.ReactDOM;var n=e.n(o);function l(e){const[o,n]=(0,r.useState)(0);return(0,t.createElement)("div",{className:"boilerplate-frontend-2"},(0,t.createElement)("p",null,"You clicked ",o," times"),(0,t.createElement)("button",{onClick:()=>n(o+1)},"Click me"))}document.querySelectorAll(".boilerplate-update-me-too").forEach((e=>{const r=JSON.parse(e.querySelector("pre").innerText);n().render((0,t.createElement)(l,r),e),e.classList.remove("boilerplate-update-me-too")}))})();