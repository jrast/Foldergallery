/*
 Shutter Reloaded
 http://www.laptoptips.ca/javascripts/shutter-reloaded/
 Version: 2.0.1
 Acknowledgement: some ideas are from: Shutter by Andrew Sutherland - http://code.jalenack.com, WordPress - http://wordpress.org, Lightbox by Lokesh Dhakar - http://www.huddletogether.com
 Released under the GPL, http://www.gnu.org/copyleft/gpl.html
 Copyright (C) 2007  Andrew Ozz
 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 
 Modified for Websitebaker CMS by Juerg Rast
 Date: 12.2.2009
 Thank you Andrew Sutherland for this great lightbox tool!
 */
shutterReloaded = {
    
    I: function(a){
        return document.getElementById(a);
    },
    
    settings: function(){
        var t = this, s = shutterSettings;
        
        t.L10n = s.L10n || ['Previous', 'Next', 'Close', 'Full Size', 'Fit to Screen', 'Image', 'of', 'Loading...'];
        t.imageCount = s.imageCount || 0;
        t.textBtns = s.textBtns || 0;
        t.imgDir = s.imgDir || '/wp-content/plugins/shutter-reloaded/menu/';
    },
    
    Init: function(a){
        var t = this, L, T, ext, i, setid, inset, shfile, shMenuPre, k, img;
        for (i = 0; i < document.links.length; i++) {
            L = document.links[i];
            ext = (L.href.indexOf('?') == -1) ? L.href.slice(-4).toLowerCase() : L.href.substring(0, L.href.indexOf('?')).slice(-4).toLowerCase();
            if (ext != '.jpg' && ext != '.png' && ext != '.gif' && ext != 'jpeg') 
                continue;
            if (a == 'sh' && L.className.toLowerCase().indexOf('shutter') == -1) 
                continue;
            if (a == 'lb' && L.rel.toLowerCase().indexOf('lightbox') == -1) 
                continue;
            
            if (L.className.toLowerCase().indexOf('shutterset') != -1) 
                setid = (L.className.indexOf(' ') != -1) ? L.className.slice(0, L.className.indexOf(' ')) : L.className;
            else 
                if (L.rel.toLowerCase().indexOf('lightbox[') != -1) 
                    setid = L.rel;
                else 
                    setid = 0, inset = -1;
            
            if (setid) {
                if (!shutterSets[setid]) 
                    shutterSets[setid] = [];
                inset = shutterSets[setid].push(i);
            }
            
            shfile = L.href.slice(L.href.lastIndexOf('/') + 1);
            T = (L.title && L.title != shfile) ? L.title : '';
            
            shutterLinks[i] = {
                link: L.href,
                num: inset,
                set: setid,
                title: T
            }
            L.onclick = new Function('shutterReloaded.Make("' + i + '");return false;');
        }
		
		t.settings();
        
        if (!t.textBtns) {
            shMenuPre = ['close.gif', 'prev.gif', 'next.gif', 'resize1.gif', 'resize2.gif', 'loading.gif'];
            for (k = 0; k < shMenuPre.length; k++) {
                img = new Image();
                img.src = t.imgDir + shMenuPre[k];
            }
        }
    },
    
    Make: function(ln, fs){
        var t = this, prev, next, prevlink = '', nextlink = '', previmg, nextimg, prevbtn, nextbtn, D, S, W, NB, fsarg = '', imgNum, closebtn, fsbtn, fsLink;
        
        if (!t.Top) {
            if (typeof window.pageYOffset != 'undefined') 
                t.Top = window.pageYOffset;
            else 
                t.Top = (document.documentElement.scrollTop > 0) ? document.documentElement.scrollTop : document.body.scrollTop;
        }
        
        if (typeof t.pgHeight == 'undefined') 
            t.pgHeight = Math.max(document.documentElement.scrollHeight, document.body.scrollHeight);
        
        if (fs) 
            t.FS = true;
        else 
            t.FS = null;
        
        if (t.resizing) 
            t.resizing = null;
        window.onresize = new Function('shutterReloaded.Resize("' + ln + '");');
        
        document.documentElement.style.overflowX = 'hidden';
        if (!t.VP) {
            t._viewPort();
            t.VP = true;
        }
        
        if (!(S = t.I('shShutter'))) {
            S = document.createElement('div');
            S.setAttribute('id', 'shShutter');
            document.getElementsByTagName('body')[0].appendChild(S);
            t.fixTags();
        }
        
        if (!(D = t.I('shDisplay'))) {
            D = document.createElement('div');
            D.setAttribute('id', 'shDisplay');
            D.style.top = t.Top + 'px';
            document.getElementsByTagName('body')[0].appendChild(D);
        }
        
        S.style.height = t.pgHeight + 'px';
        
        var dv = t.textBtns ? ' | ' : '';
        if (shutterLinks[ln].num > 1) {
            prev = shutterSets[shutterLinks[ln].set][shutterLinks[ln].num - 2];
            prevbtn = t.textBtns ? t.L10n[0] : '<img src="' + t.imgDir + 'prev.gif" title="' + t.L10n[0] + '" />';
            prevlink = '<a href="#" onclick="shutterReloaded.Make(' + prev + ');return false">' + prevbtn + '</a>' + dv;
            previmg = new Image();
            previmg.src = shutterLinks[prev].link;
        }
        
        if (shutterLinks[ln].num != -1 && shutterLinks[ln].num < (shutterSets[shutterLinks[ln].set].length)) {
            next = shutterSets[shutterLinks[ln].set][shutterLinks[ln].num];
            nextbtn = t.textBtns ? t.L10n[1] : '<img src="' + t.imgDir + 'next.gif" title="' + t.L10n[1] + '" />';
            nextlink = '<a href="#" onclick="shutterReloaded.Make(' + next + ');return false">' + nextbtn + '</a>' + dv;
            nextimg = new Image();
            nextimg.src = shutterLinks[next].link;
        }
        
        closebtn = t.textBtns ? t.L10n[2] : '<img src="' + t.imgDir + 'close.gif" title="' + t.L10n[2] + '" />';
        
        imgNum = ((shutterLinks[ln].num > 0) && t.imageCount) ? ' ' + t.L10n[5] + '&nbsp;' + shutterLinks[ln].num + '&nbsp;' + t.L10n[6] + '&nbsp;' + shutterSets[shutterLinks[ln].set].length : '';
        if (imgNum && t.textBtns) 
            imgNum += ' |';
        
        if (t.FS) {
            fsbtn = t.textBtns ? t.L10n[4] : '<img src="' + t.imgDir + 'resize2.gif" title="' + t.L10n[4] + '" />';
        }
        else {
            fsbtn = t.textBtns ? t.L10n[3] : '<img src="' + t.imgDir + 'resize1.gif" title="' + t.L10n[3] + '" />';
            fsarg = ',1';
        }
        
        fsLink = '<span id="fullSize"><a href="#" onclick="shutterReloaded.Make(' + ln + fsarg + ');return false">' + fsbtn + '</a>' + dv + '</span>';
        
        if (!(NB = t.I('shNavBar'))) {
            NB = document.createElement('div');
            NB.setAttribute('id', 'shNavBar');
            document.getElementsByTagName('body')[0].appendChild(NB);
        }
        
        NB.innerHTML = dv + prevlink + '<a href="#" onclick="shutterReloaded.hideShutter();return false">' + closebtn + '</a>' + dv + fsLink + nextlink + imgNum;
        
        D.innerHTML = '<div id="shWrap"><img src="' + shutterLinks[ln].link + '" id="shTopImg" onload="shutterReloaded.ShowImg();" onclick="shutterReloaded.hideShutter();" /><div id="shTitle">' + shutterLinks[ln].title + '</div></div>';
        
        window.setTimeout(function(){
            shutterReloaded.loading();
        }, 2000);
    },
    
    loading: function(){
        var t = this, S, WB, W;
        if ((W = t.I('shWrap')) && W.style.visibility == 'visible') 
            return;
        if (!(S = t.I('shShutter'))) 
            return;
        if (t.I('shWaitBar')) 
            return;
        WB = document.createElement('div');
        WB.setAttribute('id', 'shWaitBar');
        WB.style.top = t.Top + 'px';
        WB.innerHTML = '<img src="' + t.imgDir + 'loading.gif" title="' + t.L10n[7] + '" />';
        S.appendChild(WB);
    },
    
    hideShutter: function(){
        var t = this, D, S, NB;
        if (D = t.I('shDisplay')) 
            D.parentNode.removeChild(D);
        if (S = t.I('shShutter')) 
            S.parentNode.removeChild(S);
        if (NB = t.I('shNavBar')) 
            NB.parentNode.removeChild(NB);
        this.fixTags(true);
        window.scrollTo(0, t.Top);
        window.onresize = t.FS = t.Top = t.VP = null;
        document.documentElement.style.overflowX = '';
    },
    
    Resize: function(ln){
		var t = this;
        if (t.resizing) 
            return;
        if (!t.I('shShutter')) 
            return;
        var W = t.I('shWrap');
        if (W) 
            W.style.visibility = 'hidden';
        
        window.setTimeout(function(){
            shutterReloaded.resizing = null
        }, 500);
        window.setTimeout(new Function('shutterReloaded.VP = null;shutterReloaded.Make("' + ln + '");'), 100);
        t.resizing = true;
    },
    
    _viewPort: function(){
		var t = this;
        var wiH = window.innerHeight ? window.innerHeight : 0;
        var dbH = document.body.clientHeight ? document.body.clientHeight : 0;
        var deH = document.documentElement ? document.documentElement.clientHeight : 0;
        
        if (wiH > 0) {
            t.wHeight = ((wiH - dbH) > 1 && (wiH - dbH) < 30) ? dbH : wiH;
            t.wHeight = ((t.wHeight - deH) > 1 && (t.wHeight - deH) < 30) ? deH : t.wHeight;
        }
        else 
            t.wHeight = (deH > 0) ? deH : dbH;
        
        var deW = document.documentElement ? document.documentElement.clientWidth : 0;
        var dbW = window.innerWidth ? window.innerWidth : document.body.clientWidth;
        t.wWidth = (deW > 1) ? deW : dbW;
    },
    
    ShowImg: function(){
        var t = this, S, W, WB, D, T, TI, NB, wHeight, wWidth, capH, shHeight, maxHeight, itop, mtop, resized = 0;
        if (!(S = t.I('shShutter'))) 
            return;
        if ((W = t.I('shWrap')) && W.style.visibility == 'visible') 
            return;
        if (WB = t.I('shWaitBar')) 
            WB.parentNode.removeChild(WB);
        
        D = t.I('shDisplay');
        TI = t.I('shTopImg');
        T = t.I('shTitle');
        NB = t.I('shNavBar');
        S.style.width = D.style.width = '';
        T.style.width = (TI.width - 4) + 'px';
        
        capH = NB.offsetHeight ? T.offsetHeight + NB.offsetHeight : 30;
        shHeight = t.wHeight - 7 - capH;
        
        if (t.FS) {
            if (TI.width > (t.wWidth - 10)) 
                S.style.width = D.style.width = TI.width + 10 + 'px';
            document.documentElement.style.overflowX = '';
        }
        else {
            window.scrollTo(0, t.Top);
            if (TI.height > shHeight) {
                TI.width = TI.width * (shHeight / TI.height);
                TI.height = shHeight;
                resized = 1;
            }
            if (TI.width > (t.wWidth - 16)) {
                TI.height = TI.height * ((t.wWidth - 16) / TI.width);
                TI.width = this.wWidth - 16;
                resized = 1;
            }
            T.style.width = (TI.width - 4) + 'px';
            NB.style.bottom = '0px';
        }
        
        maxHeight = t.Top + TI.height + capH + 10;
        if (maxHeight > t.pgHeight) 
            S.style.height = maxHeight + 'px';
        window.scrollTo(0, t.Top);
        if ((t.FS && (TI.height > shHeight || TI.width > t.wWidth)) || resized) 
            t.I('fullSize').style.display = 'inline';
        
        itop = (shHeight - TI.height) * 0.45;
        mtop = (itop > 3) ? Math.floor(itop) : 3;
        D.style.top = t.Top + mtop + 'px';
        NB.style.bottom = '0';
        W.style.visibility = 'visible';
    },
    
    fixTags: function(arg){
        var sel = document.getElementsByTagName('select');
        var obj = document.getElementsByTagName('object');
        var emb = document.getElementsByTagName('embed');
        
        if (arg) 
            var vis = 'visible';
        else 
            var vis = 'hidden';
        
        for (i = 0; i < sel.length; i++) 
            sel[i].style.visibility = vis;
        for (i = 0; i < obj.length; i++) 
            obj[i].style.visibility = vis;
        for (i = 0; i < emb.length; i++) 
            emb[i].style.visibility = vis;
    }
}

if (typeof shutterOnload == 'function') {
    var shutterLinks = {}, shutterSets = {};
    oldonload = window.onload;
    if (typeof window.onload != 'function') 
        window.onload = shutterOnload;
    else 
        window.onload = function(){
            shutterOnload();
            if (oldonload) {
                oldonload();
            }
        };
}
