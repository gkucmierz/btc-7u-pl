// 'use strict';

(function(){

    var key = '6PfYU8C5sLGsjDNWsCHRYD6G5noFmc184Q4owtnfvXrUdpsfNkeTq2HDV8';
    var worker = $('#worker');
    var work = [];

    var log = function(text) {
        $('textarea', worker).val(
            text + '\n' +
            $('textarea', worker).val()
        );
    };

    var updateQueue = function() {
        $('.queue ul', worker).html(work.map(function(el) {
            return '<li>' + el + '</li>';
        }).join(''));
    };

    var addWork = function(data) {
        var d = JSON.parse(data);
        var initLength = work.length;
        var done;
        if (d.hasOwnProperty('concat')) {
            work = [].concat(work, d['concat']);
            log('dodałem do kolejki '+(work.length - initLength)+' haseł');
            updateQueue();
        }
        if (d.hasOwnProperty('work')) {
            done = d['work']['max'] - d['work']['left'];
            $('.info', worker).html([
                done,
                ' / ',
                d['work']['max'],
                ' - ',
                ((done/d['work']['max'])*100).toFixed(2),
                '%'
            ].join(''));
        }
    };

    var getWork = function(fn){
        log('pobieram nowe hasła z serwera')
        $.ajax({
            type: 'POST',
            url: 'post.php',
            success: function(data){
                addWork(data);
                fn();
            }
        });
    };

    var startWork = function() {
        if (work.length === 0) {
            return getWork(startWork);
        }

        calculate(work.shift());
        updateQueue();
    };

    var calculate = function(pass) {
        var res, start, stop;
        log('rozpoczynam liczenie hasła: ' + pass);

        start = new Date().getTime();
        ninja.privateKey.BIP38EncryptedKeyToByteArrayAsync(key, pass, function (btcKeyOrError) {
            var btcKey, priv = '';
            if (btcKeyOrError.message) {
                res = 0;
            } else {
                res = 1;
                btcKey = new Bitcoin.ECKey(btcKeyOrError);
                btcKey.setCompressed(false);
                priv = btcKey.toString().toUpperCase();
            }
            stop = new Date().getTime();
            log('benchmark: ' + (stop-start) + ' ms');


            sendRes(pass, res, priv);
            startWork();
        });

        // setTimeout(function() {
        //     res = 0;
        //     sendRes(pass, res);
        //     startWork();
        // }, 1e3);
    };

    var sendRes = function(pass, res, priv) {
        var start, stop;
        var author = $('input', worker).val();
        log('przesyłam wyniki obliczeń do serwera ('+ author +')');
        start = new Date().getTime();
        $.ajax({
            type: 'POST',
            url: 'post.php',
            data: {
                result: {
                    author: author,
                    pass: pass,
                    res: res,
                    priv: priv
                },
                queue: work.length
            },
            success: function(data){
                stop = new Date().getTime();
                log('odpowiedź z serwera trwała: ' + (stop-start) + ' ms');
                addWork(data);
            }
        });
    };

    $('button', worker).on('click', function(){
        $(this).addClass('mining');
        $(this).attr('disabled', true);

        startWork();
    });

    (function(){
        var m = location.href.match(/#([\w@\+\.]*)/i);
        if (m) {
            $('input', worker).val(m[1]);
            $('button', worker).click();
        }
    })();

    // (function(){
    //     var testKey = '6PfNz1qkNdq1sRXY559W6pHCFPxGybHUkrD1eqohrcQTfvV9VUzemjBunN';
    //     ninja.privateKey.BIP38EncryptedKeyToByteArrayAsync(testKey, 'a', function (btcKeyOrError) {
    //         var btcKey, priv = '';
    //         if (btcKeyOrError.message) {
    //             res = 0;
    //             alert('test failed');
    //         } else {
    //             res = 1;
    //             btcKey = new Bitcoin.ECKey(btcKeyOrError);
    //             btcKey.setCompressed(false);
    //             priv = btcKey.toString().toUpperCase();
    //             alert('priv: ' + priv)
    //         }
    //     });
    // })();

})();