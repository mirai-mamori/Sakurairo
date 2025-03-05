wp.hooks.addFilter("kirkiPostMessageStylesOutput","kirki",(function(r,i,t,e){if("kirki-margin"!==e&&"kirki-padding"!==e)return r;if(!(i.top||i.right||i.bottom||i.left))return r;var k=e.replace("kirki-","");for(var a in r+=t.element+"{",i)if(Object.hasOwnProperty.call(i,a)){var n=i[a];""!==n&&(r+=k+"-"+a+": "+n+";")}return r+="}"}));
//# sourceMappingURL=preview.js.map
