(window.webpackJsonp=window.webpackJsonp||[]).push([[2],{10:function(t,e,r){"use strict";r.r(e);var n=r(0),a=r.n(n);function o(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){var r=null==t?null:"undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(null==r)return;var n,a,o=[],l=!0,u=!1;try{for(r=r.call(t);!(l=(n=r.next()).done)&&(o.push(n.value),!e||o.length!==e);l=!0);}catch(t){u=!0,a=t}finally{try{l||null==r.return||r.return()}finally{if(u)throw a}}return o}(t,e)||function(t,e){if(!t)return;if("string"==typeof t)return l(t,e);var r=Object.prototype.toString.call(t).slice(8,-1);"Object"===r&&t.constructor&&(r=t.constructor.name);if("Map"===r||"Set"===r)return Array.from(t);if("Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r))return l(t,e)}(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function l(t,e){(null==e||e>t.length)&&(e=t.length);for(var r=0,n=new Array(e);r<e;r++)n[r]=t[r];return n}e.default=function(t){var e="";t.dataSource&&(e=JSON.parse(t.dataSource).options);var r=o(Object(n.useState)(e),2),l=r[0],u=r[1];return a.a.createElement("div",{className:"form-group item_options "},a.a.createElement("label",{className:"left control-label"},"选项："),a.a.createElement("div",{className:"right"},a.a.createElement("input",{type:"hidden",name:"options",value:l}),a.a.createElement("input",{type:"text",className:"form-control input text",value:l,onChange:function(t){u(t.target.value)}}),a.a.createElement("span",{className:"check-tips small"},"格式为xxx,xxx,xxx")))}}}]);