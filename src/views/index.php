<?php
?>
<style>
    .public-page {
        display: inline-block;
        *display: inline;
        *zoom: 1;
        vertical-align: middle;
        margin: 10px 0;
        font-size: 0;
    }


    .public-page>a:first-child, .public-page>a:first-child em {
        border-radius: 2px 0 0 2px;
    }
    .public-page>*:first-child {
        margin-left: 0!important;
    }
    .public-page a {
        text-decoration: none;
    }
    .public-page a, .public-page span {
        display: inline-block;
        *display: inline;
        *zoom: 1;
        vertical-align: middle;
        padding: 0 15px;
        height: 28px;
        line-height: 28px;
        margin: 0 -1px 5px 0;
        background-color: #fff;
        color: #333;
        font-size: 12px;
    }

    .public-page .public-page-curr {
        position: relative;
    }
    .public-page a, .public-page span {
        display: inline-block;
        *display: inline;
        *zoom: 1;
        vertical-align: middle;
        padding: 0 15px;
        height: 28px;
        line-height: 28px;
        margin: 0 -1px 5px 0;
        background-color: #fff;
        color: #333;
        font-size: 12px;
    }
    .public-page a, .public-page span, .public-page input, .public-page button, .public-page select {
        border: 1px solid #eee;
    }
    .public-page-box, .public-page-box * {
        box-sizing: content-box;
    }
    .public-page {
        display: inline-block;
        *display: inline;
        *zoom: 1;
        vertical-align: middle;
        margin: 10px 0;
        font-size: 0;
    }
    .public-page .public-page-curr .public-page-em {
        position: absolute;
        left: -1px;
        top: -1px;
        padding: 1px;
        width: 100%;
        height: 100%;
        background-color: #009688;
    }

    .public-page .public-page-curr em {
        position: relative;
        color: #fff;
    }
    .public-page em {
        font-style: normal;
    }
    .public-page-em {
        border-radius: 2px;
    }
    .public-page-box, .public-page-box * {
        box-sizing: content-box;
    }
    .public-page .public-page-limits, .public-page .public-page-refresh {
        vertical-align: top;
    }

    .public-page .public-page-count, .public-page .public-page-limits, .public-page .public-page-refresh, .public-page .public-page-skip {
        margin-left: 10px;
        margin-right: 10px;
        padding: 0;
        border: none;
    }
    .public-page>*:last-child {
        margin-right: 0!important;
    }
    .public-page-disabled, .public-page-disabled:hover {
        color: #d2d2d2 !important;
        cursor: not-allowed !important;
    }
    .public-page select {
        height: 22px;
        padding: 3px;
        border-radius: 2px;
        cursor: pointer;
    }
</style>
<div id="page"></div>
<script>

/**
 @Name : ????????????
 @License???MIT
 */
    (function (win) {
        var doc = document
            ,id = 'getElementById'
            ,tag = 'getElementsByTagName'

            //????????????
            ,MOD_NAME = 'publicPage', DISABLED = 'public-page-disabled'

            //?????????
            ,Class = function(options){
                var that = this;
                that.config = options || {};
                that.config.index = ++publicPage.index;
                that.render(true);
            };

        //???????????????????????????
        Class.prototype.type = function(){
            var config = this.config;
            if(typeof config.elem === 'object'){
                return config.elem.length === undefined ? 2 : 3;
            }
        };

        //??????
        let each = function(obj, fn){
            var key
                ,that = this
                ,callFn = function(key, obj){ //??????
                return fn.call(obj[key], key, obj[key])
            };

            if(typeof fn !== 'function') return that;
            obj = obj || [];

            //????????????????????????
            for(key = 0; key < obj.length; key++){
                if(callFn(key, obj)) break;
            }
            // if(isArray(obj)){
            //     for(key = 0; key < obj.length; key++){
            //         if(callFn(key, obj)) break;
            //     }
            // } else {
            //     for(key in obj){
            //         if(callFn(key, obj)) break;
            //     }
            // }

            return that;
        };

        //????????????
        Class.prototype.view = function(){
            var that = this
                ,config = that.config
                ,groups = config.groups = 'groups' in config ? (config.groups|0) : 5; //??????????????????

            //??????
            config.layout = typeof config.layout === 'object'
                ? config.layout
                : ['prev', 'page', 'next'];

            config.count = config.count|0; //????????????
            config.curr = (config.curr|0) || 1; //?????????

            //????????????????????????
            config.limits = typeof config.limits === 'object'
                ? config.limits
                : [10, 20, 30, 40, 50];
            config.limit = (config.limit|0) || 10; //????????????

            //?????????
            config.pages = Math.ceil(config.count/config.limit) || 1;

            //??????????????????????????????
            if(config.curr > config.pages){
                config.curr = config.pages;
            }

            //??????????????????????????????0????????????????????????
            if(groups < 0){
                groups = 1;
            } else if (groups > config.pages){
                groups = config.pages;
            }

            config.prev = 'prev' in config ? config.prev : '?????????'; //???????????????
            config.next = 'next' in config ? config.next : '?????????'; //???????????????

            //???????????????
            var index = config.pages > groups
                ? Math.ceil( (config.curr + (groups > 1 ? 1 : 0)) / (groups > 0 ? groups : 1) )
                : 1

                //????????????
                ,views = {
                    //?????????
                    prev: function(){
                        return config.prev
                            ? '<a href="javascript:;" class="public-page-prev'+ (config.curr == 1 ? (' ' + DISABLED) : '') +'" data-page="'+ (config.curr - 1) +'">'+ config.prev +'</a>'
                            : '';
                    }()

                    //??????
                    ,page: function(){
                        var pager = [];

                        //????????????0?????????????????????
                        if(config.count < 1){
                            return '';
                        }

                        //??????
                        if(index > 1 && config.first !== false && groups !== 0){
                            pager.push('<a href="javascript:;" class="public-page-first" data-page="1"  title="&#x9996;&#x9875;">'+ (config.first || 1) +'</a>');
                        }

                        //?????????????????????????????????
                        var halve = Math.floor((groups-1)/2) //???????????????
                            ,start = index > 1 ? config.curr - halve : 1
                            ,end = index > 1 ? (function(){
                            var max = config.curr + (groups - halve - 1);
                            return max > config.pages ? config.pages : max;
                        }()) : groups;

                        //?????????????????????????????????????????????????????????
                        if(end - start < groups - 1){
                            start = end - groups + 1;
                        }

                        //??????????????????
                        if(config.first !== false && start > 2){
                            pager.push('<span class="public-page-spr">&#x2026;</span>')
                        }

                        //??????????????????
                        for(; start <= end; start++){
                            if(start === config.curr){
                                //?????????
                                pager.push('<span class="public-page-curr"><em class="public-page-em" '+ (/^#/.test(config.theme) ? 'style="background-color:'+ config.theme +';"' : '') +'></em><em>'+ start +'</em></span>');
                            } else {
                                pager.push('<a href="javascript:;" data-page="'+ start +'">'+ start +'</a>');
                            }
                        }

                        //???????????????????????? & ??????
                        if(config.pages > groups && config.pages > end && config.last !== false){
                            if(end + 1 < config.pages){
                                pager.push('<span class="public-page-spr">&#x2026;</span>');
                            }
                            if(groups !== 0){
                                pager.push('<a href="javascript:;" class="public-page-last" title="&#x5C3E;&#x9875;"  data-page="'+ config.pages +'">'+ (config.last || config.pages) +'</a>');
                            }
                        }

                        return pager.join('');
                    }()

                    //?????????
                    ,next: function(){
                        return config.next
                            ? '<a href="javascript:;" class="public-page-next'+ (config.curr == config.pages ? (' ' + DISABLED) : '') +'" data-page="'+ (config.curr + 1) +'">'+ config.next +'</a>'
                            : '';
                    }()

                    //????????????
                    ,count: '<span class="public-page-count">??? '+ config.count +' ???</span>'

                    //????????????
                    ,limit: function(){
                        var options = ['<span class="public-page-limits"><select lay-ignore>'];
                        each(config.limits, function(index, item){
                            options.push(
                                '<option value="'+ item +'"'
                                +(item === config.limit ? 'selected' : '')
                                +'>'+ item +' ???/???</option>'
                            );
                        });
                        return options.join('') +'</select></span>';
                    }()

                    //???????????????
                    ,refresh: ['<a href="javascript:;" data-page="'+ config.curr +'" class="public-page-refresh">'
                        ,'<i class="public-page-icon public-page-icon-refresh"></i>'
                        ,'</a>'].join('')

                    //????????????
                    ,skip: function(){
                        return ['<span class="public-page-skip">&#x5230;&#x7B2C;'
                            ,'<input type="text" min="1" value="'+ config.curr +'" class="public-page-input">'
                            ,'&#x9875;<button type="button" class="public-page-btn">&#x786e;&#x5b9a;</button>'
                            ,'</span>'].join('');
                    }()
                };

            return ['<div class="public-page-box public-page public-page-'+ (config.theme ? (
                /^#/.test(config.theme) ? 'molv' : config.theme
            ) : 'default') +'" id="public-page-'+ config.index +'">'
                ,function(){
                    var plate = [];
                    each(config.layout, function(index, item){
                        if(views[item]){
                            plate.push(views[item])
                        }
                    });
                    return plate.join('');
                }()
                ,'</div>'].join('');
        };

        //???????????????
        Class.prototype.jump = function(elem, isskip){
            if(!elem) return;
            var that = this
                ,config = that.config
                ,childs = elem.children
                ,btn = elem[tag]('button')[0]
                ,input = elem[tag]('input')[0]
                ,select = elem[tag]('select')[0]
                ,skip = function(){
                var curr = input.value.replace(/\s|\D/g, '')|0;
                if(curr){
                    config.curr = curr;
                    that.render();
                }
            };

            if(isskip) return skip();

            //??????
            for(var i = 0, len = childs.length; i < len; i++){
                if(childs[i].nodeName.toLowerCase() === 'a'){
                    publicPage.on(childs[i], 'click', function(){
                        var curr = this.getAttribute('data-page')|0;
                        if(curr < 1 || curr > config.pages) return;
                        config.curr = curr;
                        that.render();
                    });
                }
            }

            //??????
            if(select){
                publicPage.on(select, 'change', function(){
                    var value = this.value;
                    if(config.curr*value > config.count){
                        config.curr = Math.ceil(config.count/value);
                    }
                    config.limit = value;
                    that.render();
                });
            }

            //??????
            if(btn){
                publicPage.on(btn, 'click', function(){
                    skip();
                });
            }
        };

        //?????????????????????
        Class.prototype.skip = function(elem){
            if(!elem) return;
            var that = this, input = elem[tag]('input')[0];
            if(!input) return;
            publicPage.on(input, 'keyup', function(e){
                var value = this.value
                    ,keyCode = e.keyCode;
                if(/^(37|38|39|40)$/.test(keyCode)) return;
                if(/\D/.test(value)){
                    this.value = value.replace(/\D/, '');
                }
                if(keyCode === 13){
                    that.jump(elem, true)
                }
            });
        };

        //????????????
        Class.prototype.render = function(load){
            var that = this
                ,config = that.config
                ,type = that.type()
                ,view = that.view();

            if(type === 2){
                config.elem && (config.elem.innerHTML = view);
            } else if(type === 3){
                config.elem.html(view);
            } else {
                if(doc[id](config.elem)){
                    doc[id](config.elem).innerHTML = view;
                }
            }

            config.jump && config.jump(config, load);

            var elem = doc[id]('public-page-' + config.index);
            that.jump(elem);

            if(config.hash && !load){
                location.hash = '!'+ config.hash +'='+ config.curr;
            }

            that.skip(elem);
        };

        //????????????
        var publicPage = {
            //????????????
            render: function(options){
                var o = new Class(options);
                return o.index;
            }
            ,index: this ? (this.index + 10000) : 0
            ,on: function(elem, even, fn){
                elem.attachEvent ? elem.attachEvent('on'+ even, function(e) { //for ie
                    e.target = e.srcElement;
                    fn.call(elem, e);
                }) : elem.addEventListener(even, fn, false);
                return this;
            }
        }
        win.page = publicPage;
    })(window);

    page.render({
        elem: 'page'
        ,count: <?=$count?> //?????????????????????????????????
        ,layout: JSON.parse('<?=json_encode($layout)?>')
        ,theme: '<?=$theme?>'
        ,prev: '<?=$prev?>'
        ,next: '<?=$next?>'
        ,first: '<?=$first?>'
        ,last: '<?=$last?>'
        ,groups: '<?=$groups?>'
        ,curr: <?=$curr?>
        ,limits: JSON.parse('<?=json_encode($limits)?>')
        ,limit: <?=$limit?>
        ,jump: function(obj, first){
            //obj????????????????????????????????????
            if(!first){
                <?php if (!empty($url)):?>
                let url = '<?=$url?>';
                if (url.indexOf("?") != -1)
                {
                    window.location.href = `<?=$url?>&pageIndex=${obj.curr}&pageSize=${obj.limit}`
                }
                else
                {
                    window.location.href = `<?=$url?>?pageIndex=${obj.curr}&pageSize=${obj.limit}`
                }
                <?php else:?>
                <?php
                if (empty($callbackData))
                {
                    echo "{$callback}(obj.curr, obj.limit)";
                }
                else
                {
                    if (is_string($callbackData))
                    {
                        echo "{$callback}(obj.curr, obj.limit, '{$callbackData}')";
                    }
                    elseif (is_array($callbackData))
                    {
                        echo "{$callback}(obj.curr, obj.limit, JSON.parse('" . json_encode($callbackData) . "'))";
                    }
                }
                ?>
                <?php endif;?>
            }
        }
    });
</script>
