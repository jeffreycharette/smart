var mpBxClsBtnF = new Image();
var mpBxFwd = new Image();
var mpBxBwd = new Image();
var mpBxFwdF = new Image();
var mpBxBwdF = new Image();
var mpBxLtAw = new Image();
var mpBxLtAwF = new Image();
var mpBxLtAwF2 = new Image();
var mpBxRtAw = new Image();
var mpBxRtAwF = new Image();
var mpBxRtAwF2 = new Image();
mpBxClsBtnF.src = "/js/mopBox/sldClsF.png";
mpBxFwd.src = "/js/mopBox/sldRtB.png";
mpBxBwd.src = "/js/mopBox/sldLtB.png";
mpBxFwdF.src = "/js/mopBox/sldRtBF.png";
mpBxBwdF.src = "/js/mopBox/sldLtBF.png";
mpBxLtAw.src = "/js/mopBox/sldBtnLt.png";
mpBxLtAwF.src = "/js/mopBox/sldBtnLt.png";
mpBxLtAwF2.src = "/js/mopBox/sldBtnLt.png";
mpBxRtAw.src = "/js/mopBox/addnew.png";
mpBxRtAwF.src = "/js/mopBox/addnew_hover.png";
mpBxRtAwF2.src = "/js/mopBox/addnew_hover.png";
var mpBxCnt = 0;
var mpBxNum = 0;
var mpBxPs;
var mpBxCt = "";
var mpBxSldPs, mpBxP;
var mpBxTgt, mpBxPgH, mpBxPgW, mpBxSpd, mpBxStp, mpBxStpPx, mpBxStP, mpBxBgc, mpBxRz, mpBxRszTg;
var mpBxNvPs, mpBxFbPs, mpBxBtnW;
var mpBxCkNum, mpBxCkNum2 = 0;
var mpBxSldW, mpBxSldMx, mpBxSldDg;
var mpBxCntH = 0,
    mpBxCntW = 0;
var mpBxUa, mpBxMac, mpBxWin, mpBxBrw, mpBxIe = false;
var hvChkItv, hvChkItv2, mpBxBtnCk = "n",
    mpBxMsOv = "n",
    mpBxMsOv2 = "n";
var idCheck = [];
jQuery.fn.extend({
    mopBox: function (stt) {
        var mpBxF, thsCnt;
        var stopNum = [];
        var check, check2, check3;
        var startH, stTgtH, startW, stTgtW;
        var kpH, nwH, kpW, nwW;
        var mpBxRzY, mpBxRzX;
        var tgtH, tgtHtKp;
        var tgtW, tgtWdKp;
        var chkId;
        var dblclk = false;
        var hldLtMx;
        var hvChk, clk = 'n',
            ltClk = 'n',
            rtClk = 'n';
        var hvChk2, hvChk3;
        $(this).click(function () {
            if ($(this).hasClass('hideme')) {
                return;
            }
            $('#blanket').fadeIn();
            if (mpBxCnt == 0) {
                mpBxUa = navigator.userAgent;
                if (mpBxUa.indexOf("Mac", 0) >= 0) {
                    mpBxMac = true;
                } else if (mpBxUa.indexOf("Win", 0) >= 0) {
                    mpBxWin = true;
                };
                if (mpBxUa.indexOf("MSIE 6") > -1) {
                    mpBxBrw = "ie6";
                };
                if (mpBxUa.indexOf("MSIE 7") > -1) {
                    mpBxBrw = "ie7";
                };
                if (mpBxUa.indexOf("MSIE") > -1) {
                    mpBxIe = true;
                };
            };
            var href = $(this).attr("href");
            if ($(this).attr("href")) {
                $(this).attr({
                    href: "#hatchware"
                });
            };
            mpBxCnt += 1;
            thsCnt = jQuery.data(this);
            mpBxF.init();
            if (!$('#mpBx .holder .box:eq(' + mpBxNum + ') .mceEditor').length) {
                $('#mpBx .holder .box:eq(' + mpBxNum + ') .textarea').tinymce();
                $('#mpBx .holder .box:eq(' + mpBxNum + ') .textarea').each(function () {
                    toogleEditorMode(this.id);
                });
                $('#mpBx .holder .box:eq(' + mpBxNum + ') .date').each(function (i) {
                    var did = $(this).attr('id');
                    $(this).DatePicker({
                        format: 'm/d/Y',
                        date: $('#' + did).val(),
                        starts: 0,
                        calendars: 1,
                        view: 'days',
                        onBeforeShow: function () {
                            if ($('#' + did).val() != "") {
                                $('#' + did).DatePickerSetDate($('#' + did).val(), true);
                            }
                        },
                        onChange: function (formated, dates) {
                            $('#' + did).val(formated).removeClass('suggest').DatePickerHide();
                        }
                    });
                });
                $('#mpBx .holder .box:eq(' + mpBxNum + ') .daterange').each(function (i) {
                    var did = $(this).attr('id');
                    $(this).DatePicker({
                        format: 'm/d/Y',
                        date: $('#' + did).val(),
                        starts: 0,
                        calendars: 3,
                        mode: 'range',
                        view: 'days',
                        onBeforeShow: function () {
                            if ($('#' + did).val() != "") {
                                var tval = $('#' + did).val() + "," + $('#' + did).parent().parent().find('.date:eq(0)').val();
                                $('#' + did).DatePickerSetDate(tval.split(","), true)
                            } else {
                                $('#' + did).DatePickerClear();
                            }
                        },
                        onChange: function (formated, dates) {
                            var date = formated.toString().split(",");
                            $('#' + did).val(date[0]).removeClass('suggest').parent().parent().find('.date').val(date[1]);
                        }
                    });
                });
                $('#mpBx .holder .box:eq(' + mpBxNum + ') .multiple').each(function (i) {
                    var did = $(this).attr('id');
                    $(this).DatePicker({
                        format: 'm/d/Y',
                        date: $('#' + did).val().split(","),
                        starts: 0,
                        calendars: 2,
                        mode: 'multiple',
                        view: 'days',
                        position: 'left',
                        onBeforeShow: function () {
                            if ($('#' + did).val() != "") {
                                $('#' + did).DatePickerSetDate($('#' + did).val().split(","), true);
                            } else {
                                $('#' + did).DatePickerClear();
                            }
                        },
                        onChange: function (formated, dates) {
                            $('#' + did).val(formated).removeClass('suggest');
                        }
                    });
                });
            }
            $('img.calicon').click(function () {
                $('#' + this.alt).focus();
            });
            $('.limit').each(function (i) {
                var el = $(this).attr("id");
                var limit_info = $('.limit_info:eq(' + i + ')').attr("id");
                var limit = $('.limit_info:eq(' + i + ')').find(':text').val();
                $(this).keyup(function () {
                    limitChars(el, limit_info, limit);
                });
            });
            $('.url').each(function (i) {
                var el = $(this).attr("id");
                var info = $('.url_info:eq(' + i + ')').attr("id");
                var ptn = /[^a-z^0-9^_-]/i;
                $(this).keyup(function () {
                    changeChars(el, info, ptn);
                });
            });
            $('.special').each(function (i) {
                var el = $(this).attr("id");
                var url_info = $('.special_info:eq(' + i + ')').attr("id");
                var ptn = /[^a-z^0-9^_ -]/i;
                $(this).keyup(function () {
                    changeChars(el, url_info, ptn);
                });
            });
            $('.occurs').change(function () {
                var val = $(this).val();
                var tid = $(this).attr('id');
                if (val == 'once') {
                    $('.' + tid).fadeOut();
                    $('.dates_' + tid).fadeOut();
                } else if (val == 'select') {
                    $('.' + tid).fadeOut();
                    $('.dates_' + tid).fadeIn();
                } else {
                    $('.' + tid).fadeIn();
                    $('.dates_' + tid).fadeOut();
                }
            });
            $(".upload").editable("edit.json", {
                name: "pictures[]",
                indicator: "<img src='/img/ajax-loader.gif' />",
                type: 'ajaxupload',
                submit: 'Upload',
                tooltip: "Click to upload"
            });
            $('.imglist a').lightBox();
						$('#jquery-lightbox').live('click', function(el) {
							$(this).find('#lightbox-image').imgAreaSelect({
					        handles: true,
					        onSelectEnd: function (img, selection) {
									  alert('width: ' + selection.width + '; height: ' + selection.height);
									}
					    });
						});
        });
        mpBxF = {
            init: function () {
                mpBxTgt = stt.target;
                mpBxPgW = stt.w + 40;
                mpBxPgH = stt.h + 40;
                mpBxSpd = stt.speed;
                mpBxStp = stt.step;
                mpBxStpPx = stt.stepPx;
                mpBxNvPs = stt.naviPosi;
                mpBxFbPs = stt.fbPosi;
                mpBxBtnW = stt.btnW;
                mpBxStP = stt.startPage;
                mpBxBgc = stt.bgc;
                mpBxRz = stt.resize;
                mpBxRszTg = stt.rszTarget;
                var mopBoxFnc = function () {
                        if (stt.fnc != null) {
                            if (stt.fnc == "pChange") {
                                news.pChange();
                            };
                            if (stt.fnc == "demoFnc") {
                                demoFnc();
                            };
                        };
                    };
                $("#mpBx .sliderBtn").draggable("destroy");
                $("#mpBx .case").resizable('destroy');
                $("#mpBx .holder").children().remove();
                if (mpBxStp == null) {
                    mpBxStp = 1;
                };
                if (mpBxStpPx == null) {
                    mpBxStpPx = 10;
                };
                if (mpBxSpd == null) {
                    mpBxSpd = 300;
                };
                if (mpBxNvPs == null) {
                    mpBxNvPs = 5;
                };
                if (mpBxFbPs == null) {
                    mpBxFbPs = 50;
                };
                if (mpBxBtnW == null) {
                    mpBxBtnW = 100;
                };
                if (mpBxStP == null) {
                    mpBxStP = 1;
                };
                if (mpBxRz == "se") {
                    mpBxRz = "s,e,se"
                }
                mpBxCkNum = mpBxStpPx / mpBxStp;
                if (mpBxCnt == 1) {
                    $("body").append('<div id="mpBox"><div id="mpBx" class="dialog">' + '<div class="s-topLeft"></div>' + '<div class="s-top"></div>' + '<div class="s-left"></div>' + '<div class="s-topRight"></div>' + '<div class="s-right"></div>' + '<div class="s-bottomLeft"></div>' + '<div class="s-bottom"></div>' + '<div class="s-bottomRight"></div>' + '<div class="cover"></div>' + '<div class="case">' + '<div class="holder"></div>' + '</div>' + '<div class="fwd"></div>' + '<div class="bwd"></div>' + '<div class="sldBtnRight"></div>' + '<div class="slider">' + '<div class="sldLeft"></div>' + '<div class="sldCenter"></div>' + '<div class="sldRight"></div>' + '<div class="sliderBtn">' + '<div class="sldBtnLeft"></div>' + '<div class="sldBtnCenter"><div class="pageNumber"></div></div>' + '<div class="blankRight"></div>' + '</div>' + '</div>' + '<div class="closeBtn"></div>' + '</div></div>');
                    $(".closeBtn").click(function () {
                        $("#mpBx .sliderBtn").draggable("destroy");
                        $("#mpBx .slider,#mpBx .fwd,#mpBx .bwd").hide();
                        $("#mpBx .s-topLeft, #mpBx .s-top, #mpBx .s-left, #mpBx .s-topRight, #mpBx .s-right").hide();
                        $("#mpBx .s-bottomLeft, #mpBx .s-bottom, #mpBx .s-bottomRight,#mpBx .closeBtn,#mpBx object").hide();
                        $("#mpBx").fadeOut("slow");
                        $('.picker').remove();
                        $('#blanket').fadeOut();
                        removeAllMCE();
                    });
                    if (mpBxBrw != "ie6") {
                        $("#mpBx .pageNumber").mouseover(function () {
                            if ((mpBxBtnCk == 'n') && (clk == 'n')) {
                                mpBxMsOv = 'y';
                                clearInterval(hvChk);
                                hvChk = setInterval("hvChkItv()", 20);
                            }
                        });
                        $("#mpBx .pageNumber").mouseout(function () {
                            if ((mpBxBtnCk == 'n') && (clk == 'n')) {
                                clearInterval(hvChk);
                                $("#mpBx .sldBtnLeft").css({
                                    backgroundImage: "url(" + mpBxLtAw.src + ")"
                                });
                                $("#mpBx .sldBtnRight").css({
                                    backgroundImage: "url(" + mpBxRtAw.src + ")"
                                });
                            }
                            mpBxMsOv = 'n';
                        });
                        $("#mpBx .pageNumber").mouseup(function () {
                            mpBxBtnCk = 'n';
                        });
                        $("#mpBx .pageNumber").mousedown(function () {
                            mpBxBtnCk = 'y';
                        });
                        $("body").mouseup(function () {
                            if ((mpBxMsOv == 'n') && (mpBxBtnCk == 'y')) {
                                mpBxBtnCk = 'n';
                                clearInterval(hvChk);
                                $("#mpBx .sldBtnLeft").css({
                                    backgroundImage: "url(" + mpBxLtAw.src + ")"
                                });
                                $("#mpBx .sldBtnRight").css({
                                    backgroundImage: "url(" + mpBxRtAw.src + ")"
                                });
                            };
                        });
                        $("#mpBx .sldBtnRight").hover(function () {
                            if ((mpBxNum != (mpBxP - 1)) && (clk == 'n')) {
                                $(this).css({
                                    backgroundImage: "url(" + mpBxRtAwF2.src + ")"
                                });
                            };
                        }, function () {
                            if ((rtClk == 'n') && (clk == 'n')) {
                                $(this).css({
                                    backgroundImage: "url(" + mpBxRtAw.src + ")"
                                });
                            }
                        });
                        $("#mpBx .fwd").hover(function () {
                            mpBxMsOv2 = 'yf';
                            clearInterval(hvChk2);
                            hvChk2 = setInterval("hvChkItv2()", 20);
                        }, function () {
                            clearInterval(hvChk2);
                            $("#mpBx .fwd").css({
                                backgroundImage: "url(" + mpBxFwd.src + ")"
                            });
                            $("#mpBx .sldBtnRight").css({
                                backgroundImage: "url(" + mpBxRtAw.src + ")"
                            });
                        });
                        $("#mpBx .bwd").hover(function () {
                            mpBxMsOv2 = 'yb';
                            clearInterval(hvChk3);
                            hvChk3 = setInterval("hvChkItv3()", 20);
                        }, function () {
                            clearInterval(hvChk3);
                            $("#mpBx .bwd").css({
                                backgroundImage: "url(" + mpBxBwd.src + ")"
                            });
                            $("#mpBx .sldBtnLeft").css({
                                backgroundImage: "url(" + mpBxLtAw.src + ")"
                            });
                        });
                    }
                    $("#mpBx .fwd").click(function (e) {
                        mpBxF.goAndBack("fwd");
                        e.preventDefault();
                    });
                    $("#mpBx .bwd").click(function (e) {
                        mpBxF.goAndBack("bwd")
                        e.preventDefault();
                    });
                    var stop0 = function () {
                            clk = 'n'
                            ltClk = 'n';
                            clearInterval(check2);
                            $("#mpBx .pageNumber").html(1);
                            $("#mpBx .holder").css({
                                left: "0px"
                            });
                            mpBxNum = 0;
                            if (mpBxBrw != "ie6") {
                                $("#mpBx .sldBtnLeft").css({
                                    backgroundImage: "url(" + mpBxLtAw.src + ")"
                                });
                            };
                        };
                    var stopMax = function () {
                            clk = 'n'
                            rtClk = 'n';
                            clearInterval(check2);
                            $("#mpBx .pageNumber").html(mpBxP);
                            $("#mpBx .holder").css({
                                left: hldLtMx + "px"
                            });
                            mpBxNum = mpBxP - 1;
                            if (mpBxBrw != "ie6") {
                                $("#mpBx .sldBtnRight").css({
                                    backgroundImage: "url(" + mpBxRtAw.src + ")"
                                });
                            };
                            if (!$('#mpBx .holder .box:eq(' + mpBxNum + ') .mceEditor').length) {
                                $('#mpBx .holder .box:eq(' + mpBxNum + ') .textarea').tinymce();
                                $('#mpBx .holder .box:eq(' + mpBxNum + ') .textarea').each(function () {
                                    toogleEditorMode(this.id);
                                });
                                $('#mpBx .holder .box:eq(' + mpBxNum + ') .date').each(function (i) {
                                    var did = $(this).attr('id');
                                    $(this).DatePicker({
                                        format: 'm/d/Y',
                                        date: $('#' + did).val(),
                                        starts: 0,
                                        calendars: 1,
                                        view: 'days',
                                        onBeforeShow: function () {
                                            if ($('#' + did).val() != "") {
                                                $('#' + did).DatePickerSetDate($('#' + did).val(), true);
                                            }
                                        },
                                        onChange: function (formated, dates) {
                                            $('#' + did).val(formated).removeClass('suggest').DatePickerHide();
                                        }
                                    });
                                });
                                $('#mpBx .holder .box:eq(' + mpBxNum + ') .daterange').each(function (i) {
                                    var did = $(this).attr('id');
                                    $(this).DatePicker({
                                        format: 'm/d/Y',
                                        date: $('#' + did).val(),
                                        starts: 0,
                                        calendars: 3,
                                        mode: 'range',
                                        view: 'days',
                                        onBeforeShow: function () {
                                            if ($('#' + did).val() != "") {
                                                var tval = $('#' + did).val() + "," + $('#' + did).parent().parent().find('.date:eq(0)').val();
                                                $('#' + did).DatePickerSetDate(tval.split(","), true)
                                            } else {
                                                $('#' + did).DatePickerClear();
                                            }
                                        },
                                        onChange: function (formated, dates) {
                                            var date = formated.toString().split(",");
                                            $('#' + did).val(date[0]).removeClass('suggest').parent().parent().find('.date').val(date[1]);
                                        }
                                    });
                                });
                                $('#mpBx .holder .box:eq(' + mpBxNum + ') .multiple').each(function (i) {
                                    var did = $(this).attr('id');
                                    $(this).DatePicker({
                                        format: 'm/d/Y',
                                        date: $('#' + did).val().split(","),
                                        starts: 0,
                                        calendars: 2,
                                        mode: 'multiple',
                                        view: 'days',
                                        position: 'left',
                                        onBeforeShow: function () {
                                            if ($('#' + did).val() != "") {
                                                $('#' + did).DatePickerSetDate($('#' + did).val().split(","), true);
                                            } else {
                                                $('#' + did).DatePickerClear();
                                            }
                                        },
                                        onChange: function (formated, dates) {
                                            $('#' + did).val(formated).removeClass('suggest');
                                        }
                                    });
                                });
                            }
                        };
                    $("#mpBx .sldBtnRight").click(function () {
                        clk = 'y'
                        rtClk = 'y'
                        mpBxSldMx = mpBxSldW - mpBxBtnW;
                        hldLtMx = -(mpBxPgW * (mpBxP - 1));
                        var page = $("#mpBx .pageNumber").html();
                        if (page != mpBxP) {
                            $("#mpBx .sliderBtn").animate({
                                left: mpBxSldMx + "px"
                            }, 500, "linear", function () {
                                stopMax();
                            });
                            check2 = setInterval("mpBxSldDg2()", 10);
                        };
                    });
                };
                $("#mpBx").pngFix();
                $("#mpBx").css({
                    width: mpBxPgW + 40 + "px",
                    height: mpBxPgH + 40 + "px"
                });
                $("#mpBx .s-bottom,#mpBx .s-bottomLeft,#mpBx .s-bottomRight").css({
                    "top": mpBxPgH + 20 + "px"
                });
                $("#mpBx .case,#mpBx .s-top,#mpBx .s-bottom").css({
                    width: mpBxPgW + "px"
                });
                $("#mpBx .case,#mpBx .s-left,#mpBx .s-right").css({
                    height: mpBxPgH + "px"
                });
                $("#mpBx .s-right,#mpBx .s-topRight,#mpBx .s-bottomRight").css({
                    left: mpBxPgW + 20 + "px"
                });
                if (mpBxRz != null) {
                    if (mpBxRszTg != null) {
                        if (mpBxBrw == "ie6") {
                            $(mpBxRszTg).css({
                                display: "inline"
                            })
                        };
                        tgtH = stt.rzH;
                        tgtW = stt.rzW;
                    };
                    if ((mpBxCnt == 1) || (thsCnt != mpBxCt)) {
                        chkId = jQuery.inArray(thsCnt, idCheck);
                        if (chkId == -1) {
                            idCheck.push(thsCnt);
                        } else {
                            nwH = tgtH;
                            nwW = tgtW;
                            if (mpBxCntH != 0) {
                                if (mpBxRszTg != null) {
                                    $(mpBxRszTg).css({
                                        height: tgtH + "px"
                                    });
                                    $(mpBxRszTg).css({
                                        width: tgtW + "px"
                                    });
                                };
                            };
                        };
                    } else {
                        if (mpBxCntH != 0) {
                            mpBxCntH = mpBxPgH;
                            mpBxCntW = mpBxPgW;
                            nwH = tgtH;
                            nwW = tgtW;
                            if (mpBxRszTg != null) {
                                $(mpBxRszTg).css({
                                    height: nwH
                                });
                                $(mpBxRszTg).css({
                                    width: nwW
                                });
                            };
                            $("#mpBx .case").css({
                                height: mpBxCntH + "px"
                            });
                            $("#mpBx .case").css({
                                width: mpBxCntW + "px"
                            });
                            $("#mpBx .s-bottom,#mpBx .s-bottomLeft,#mpBx .s-bottomRight").css({
                                top: mpBxCntH + 20 + "px"
                            });
                            $("#mpBx .s-right,#mpBx .s-bottomRight,#mpBx .s-topRight").css({
                                left: mpBxCntW + 20 + "px"
                            });
                            $("#mpBx .s-left,#mpBx .s-right").css({
                                height: mpBxCntH + "px"
                            });
                            $("#mpBx .s-top,#mpBx .s-bottom").css({
                                width: mpBxCntW + "px"
                            });
                        };
                    };
                    $("#mpBx .case").resizable({
                        resize: function () {
                            if (mpBxRz != null) {
                                mpBxCntH = eval($("#mpBx .case").css("height").split("px")[0]);
                                mpBxCntW = eval($("#mpBx .case").css("width").split("px")[0]);
                                mpBxRzY = mpBxCntH - mpBxPgH;
                                mpBxRzX = mpBxCntW - mpBxPgW;
                                if (mpBxRszTg != null) {
                                    nwH = tgtH + mpBxRzY + "px"
                                    nwW = tgtW + mpBxRzX + "px"
                                    $(mpBxRszTg).css({
                                        height: nwH
                                    });
                                    $(mpBxRszTg).css({
                                        width: nwW
                                    });
                                };
                                $("#mpBx .s-bottom,#mpBx .s-bottomLeft,#mpBx .s-bottomRight").css({
                                    top: mpBxCntH + 20 + "px"
                                });
                                $("#mpBx .s-right,#mpBx .s-bottomRight,#mpBx .s-topRight").css({
                                    left: mpBxCntW + 20 + "px"
                                });
                                $("#mpBx .s-left,#mpBx .s-right").css({
                                    height: mpBxCntH + "px"
                                });
                                $("#mpBx .s-top,#mpBx .s-bottom").css({
                                    width: mpBxCntW + "px"
                                });
                            };
                        },
                        handles: mpBxRz
                    });
                } else {
                    $("#mpBx .case").resizable('destroy');
                };
                if (thsCnt != mpBxCt) {
                    mpBxCkNum2 = 0;
                    mpBxNum = mpBxStP - 1;
                    if (mpBxStP == 1) {
                        $("#mpBx .pageNumber").html(mpBxStP);
                        $("#mpBx .holder").css({
                            left: -(mpBxPgW * (mpBxStP - 1)) + "px"
                        });
                        $("#mpBx .sliderBtn").css({
                            left: (mpBxStpPx * (mpBxStP - 1)) / mpBxStp + "px"
                        });
                        mpBxCt = thsCnt;
                    };
                };
                var ready1 = $(mpBxTgt).html();
                ready1 = jQuery.trim(ready1);
                if (ready1.indexOf("<!--") == 0) {
                    var ready2 = ready1.split("<!--")[1];
                    var ready3 = ready2.split("-->")[0];
                    $("#mpBx .holder").append('<div>' + ready3 + '</div>');
                } else {
                    ($(mpBxTgt)).clone().appendTo("#mpBx .holder");
                };
                mpBxP = $("#mpBx .holder").children().children().length;
                if (mpBxP == 1) {
                    $("#mpBx .slider,#mpBx .fwd,#mpBx .bwd,#mpBx .sldBtnRight").hide();
                };
                for (i = 1; i < mpBxP; i++) {
                    var num = mpBxPgW * i;
                    stopNum.push(num);
                };
                $("#mpBx .holder").children().css({
                    width: mpBxPgW * mpBxP + "px",
                    position: "absolute"
                });
                $("#mpBx .holder").children().children().css({
                    float: "left"
                });
                $("#mpBx .holder").children().show();
                if (mpBxBgc != null) {
                    $("#mpBx .case").css({
                        backgroundColor: mpBxBgc
                    });
                } else {
                    $("#mpBx .case").css({
                        backgroundColor: ""
                    });
                };
                var sliderH = eval($("#mpBx .slider").css("height").split("px")[0]);
                var stepAll = mpBxStpPx * (mpBxP - 1);
                mpBxSldW = (stepAll / mpBxStp) + mpBxBtnW;
                var sidL = ((mpBxPgW - mpBxSldW) / 2) + 20;
                $("#mpBx .sliderBtn").css({
                    width: mpBxBtnW + "px"
                });
                $("#mpBx .slider").css({
                    width: mpBxSldW + "px",
                    left: sidL + "px",
                    top: mpBxPgH + 15 + mpBxNvPs + "px"
                });
                $("#mpBx .bwd").css({
                    left: "-40px",
                    top: mpBxPgH / 2 - 50 + "px"
                });
                $("#mpBx .fwd").css({
                    right: "-44px",
                    top: mpBxPgH / 2 - 50 + "px"
                });
                $("#mpBx .cover").css({
                    top: mpBxPgH + 20 + "px",
                    height: sliderH + mpBxNvPs + 5 + "px",
                    width: mpBxPgW + "px",
                    left: "20px"
                });
                $("#mpBx .sldLeft").css({
                    left: "0px"
                });
                $("#mpBx .sldRight").css({
                    right: "0px"
                });
                $("#mpBx .sldCenter").css({
                    left: "20px",
                    width: mpBxSldW - 40 + "px"
                });
                $("#mpBx .sldBtnLeft").css({
                    left: "0px"
                });
                $("#mpBx .sldBtnRight").css({
                    top: mpBxPgH + 20 + mpBxNvPs + "px"
                });
                $("#mpBx .sldBtnCenter").css({
                    left: "20px",
                    width: mpBxBtnW - 40 + "px"
                });
                var showParts = function () {
                        $("#mpBx .s-topLeft, #mpBx .s-top, #mpBx .s-left, #mpBx .s-topRight, #mpBx .s-right").show();
                        $("#mpBx .s-bottomLeft, #mpBx .s-bottom, #mpBx .s-bottomRight").show();
                        if (mpBxP != 1) {
                            $("#mpBx .slider,#mpBx .fwd,#mpBx .bwd,#mpBx .sldBtnRight").show();
                        }
                        $("#mpBx .closeBtn").show();
                        mopBoxFnc();
                    };
                $("#mpBx").fadeIn("normal", function () {
                    showParts();
                });
                var startAnim = function () {
                        clearInterval(check3)
                        $("#mpBx .pageNumber").html(mpBxStP);
                        $("#mpBx .holder").css({
                            left: -(mpBxPgW * (mpBxStP - 1)) + "px"
                        });
                        mpBxNum = mpBxStP - 1;
                    };
                if (thsCnt != mpBxCt) {
                    if (mpBxStP != 1) {
                        $("#mpBx .sliderBtn").animate({
                            left: (mpBxStpPx * (mpBxStP - 1)) / mpBxStp + "px"
                        }, 500, "linear", function () {
                            startAnim()
                        });
                        check3 = setInterval("mpBxSldDg2()", 10);
                        mpBxCt = thsCnt;
                    };
                };
                mpBxF.sliderDrag();
                mpBxSldMx = mpBxSldW - mpBxBtnW;
                hldLtMx = -(mpBxPgW * (mpBxP - 1));
                if (mpBxMac == true) {
                    $("#mpBx .pageNumber").css({
                        fontSize: "14px"
                    });
                };
            },
            sliderDrag: function () {
                $("#mpBx .sliderBtn").draggable({
                    axis: "x",
                    cursor: "default",
                    containment: "parent",
                    grid: [mpBxStpPx],
                    start: function () {
                        check = setInterval("mpBxSldDg()", 10);
                    },
                    drag: function () {},
                    stop: function () {
                        clearInterval(check);
                        $("#mpBx .holder").animate({
                            left: ((mpBxNum * mpBxPgW) * -1) + "px"
                        }, {
                            duration: mpBxSpd,
                            easing: 'swing'
                        });
                        if (!$('#mpBx .holder .box:eq(' + mpBxNum + ') .mceEditor').length) {
                            $('#mpBx .holder .box:eq(' + mpBxNum + ') .textarea').tinymce();
                            $('#mpBx .holder .box:eq(' + mpBxNum + ') .textarea').each(function () {
                                toogleEditorMode(this.id);
                            });
                            $('#mpBx .holder .box:eq(' + mpBxNum + ') .date').each(function (i) {
                                var did = $(this).attr('id');
                                $(this).DatePicker({
                                    format: 'm/d/Y',
                                    date: $('#' + did).val(),
                                    starts: 0,
                                    calendars: 1,
                                    view: 'days',
                                    onBeforeShow: function () {
                                        if ($('#' + did).val() != "") {
                                            $('#' + did).DatePickerSetDate($('#' + did).val(), true);
                                        }
                                    },
                                    onChange: function (formated, dates) {
                                        $('#' + did).val(formated).removeClass('suggest').DatePickerHide();
                                    }
                                });
                            });
                            $('#mpBx .holder .box:eq(' + mpBxNum + ') .daterange').each(function (i) {
                                var did = $(this).attr('id');
                                $(this).DatePicker({
                                    format: 'm/d/Y',
                                    date: $('#' + did).val(),
                                    starts: 0,
                                    calendars: 3,
                                    mode: 'range',
                                    view: 'days',
                                    onBeforeShow: function () {
                                        if ($('#' + did).val() != "") {
                                            var tval = $('#' + did).val() + "," + $('#' + did).parent().parent().find('.date:eq(0)').val();
                                            $('#' + did).DatePickerSetDate(tval.split(","), true)
                                        } else {
                                            $('#' + did).DatePickerClear();
                                        }
                                    },
                                    onChange: function (formated, dates) {
                                        var date = formated.toString().split(",");
                                        $('#' + did).val(date[0]).removeClass('suggest').parent().parent().find('.date').val(date[1]);
                                    }
                                });
                            });
                            $('#mpBx .holder .box:eq(' + mpBxNum + ') .multiple').each(function (i) {
                                var did = $(this).attr('id');
                                $(this).DatePicker({
                                    format: 'm/d/Y',
                                    date: $('#' + did).val().split(","),
                                    starts: 0,
                                    calendars: 2,
                                    mode: 'multiple',
                                    view: 'days',
                                    position: 'left',
                                    onBeforeShow: function () {
                                        if ($('#' + did).val() != "") {
                                            $('#' + did).DatePickerSetDate($('#' + did).val().split(","), true);
                                        } else {
                                            $('#' + did).DatePickerClear();
                                        }
                                    },
                                    onChange: function (formated, dates) {
                                        $('#' + did).val(formated).removeClass('suggest');
                                    }
                                });
                            });
                        }
                    }
                });
            },
            goAndBack: function (whitch) {
                if (mpBxCkNum < 1) {
                    if (mpBxCkNum2 >= 1) {
                        mpBxCkNum2 -= 1;
                    };
                    mpBxCkNum2 += mpBxCkNum;
                } else {
                    mpBxCkNum2 = mpBxCkNum;
                };
                hdPosi = eval(Math.floor($("#mpBx .holder").css("left").split("px")[0]));
                mpBxSldPs = eval(Math.floor($("#mpBx .sliderBtn").css("left").split("px")[0]));
                if (((mpBxNum + 1) < mpBxP) && (whitch == "fwd")) {
                    $("#mpBx .holder").animate({
                        left: hdPosi - mpBxPgW + "px"
                    }, {
                        duration: mpBxSpd,
                        complete: function () {
                            mpBxF.goAndBack2()
                        }
                    });
                    mpBxNum += 1;
                    $("#mpBx .pageNumber").html("" + (mpBxNum + 1));
                    if ((mpBxCkNum2 >= 1) && (mpBxSldPs < (mpBxSldMx))) {
                        $("#mpBx .sliderBtn").css({
                            left: mpBxSldPs + mpBxCkNum2 + "px"
                        });
                    };
                } else if (((mpBxNum + 1) > 1) && (whitch == "bwd")) {
                    $("#mpBx .holder").animate({
                        left: hdPosi + mpBxPgW + "px"
                    }, {
                        duration: mpBxSpd,
                        complete: function () {
                            mpBxF.goAndBack2()
                        }
                    });
                    mpBxNum -= 1;
                    $("#mpBx .pageNumber").html("" + (mpBxNum + 1));
                    if (mpBxCkNum2 >= 1) {
                        $("#mpBx .sliderBtn").css({
                            left: mpBxSldPs - mpBxCkNum2 + "px"
                        });
                    };
                }
                if (!$('#mpBx .holder .box:eq(' + mpBxNum + ') .mceEditor').length) {
                    $('#mpBx .holder .box:eq(' + mpBxNum + ') .textarea').tinymce();
                    $('#mpBx .holder .box:eq(' + mpBxNum + ') .textarea').each(function () {
                        toogleEditorMode(this.id);
                    });
                    $('#mpBx .holder .box:eq(' + mpBxNum + ') .date').each(function (i) {
                        var did = $(this).attr('id');
                        $(this).DatePicker({
                            format: 'm/d/Y',
                            date: $('#' + did).val(),
                            starts: 0,
                            calendars: 1,
                            view: 'days',
                            onBeforeShow: function () {
                                if ($('#' + did).val() != "") {
                                    $('#' + did).DatePickerSetDate($('#' + did).val(), true);
                                }
                            },
                            onChange: function (formated, dates) {
                                $('#' + did).val(formated).removeClass('suggest').DatePickerHide();
                            }
                        });
                    });
                    $('#mpBx .holder .box:eq(' + mpBxNum + ') .daterange').each(function (i) {
                        var did = $(this).attr('id');
                        $(this).DatePicker({
                            format: 'm/d/Y',
                            date: $('#' + did).val(),
                            starts: 0,
                            calendars: 3,
                            mode: 'range',
                            view: 'days',
                            onBeforeShow: function () {
                                if ($('#' + did).val() != "") {
                                    var tval = $('#' + did).val() + "," + $('#' + did).parent().parent().find('.date:eq(0)').val();
                                    $('#' + did).DatePickerSetDate(tval.split(","), true)
                                } else {
                                    $('#' + did).DatePickerClear();
                                }
                            },
                            onChange: function (formated, dates) {
                                var date = formated.toString().split(",");
                                $('#' + did).val(date[0]).removeClass('suggest').parent().parent().find('.date').val(date[1]);
                            }
                        });
                    });
                    $('#mpBx .holder .box:eq(' + mpBxNum + ') .multiple').each(function (i) {
                        var did = $(this).attr('id');
                        $(this).DatePicker({
                            format: 'm/d/Y',
                            date: $('#' + did).val().split(","),
                            starts: 0,
                            calendars: 2,
                            mode: 'multiple',
                            view: 'days',
                            position: 'left',
                            onBeforeShow: function () {
                                if ($('#' + did).val() != "") {
                                    $('#' + did).DatePickerSetDate($('#' + did).val().split(","), true);
                                } else {
                                    $('#' + did).DatePickerClear();
                                }
                            },
                            onChange: function (formated, dates) {
                                $('#' + did).val(formated).removeClass('suggest');
                            }
                        });
                    });
                }
            },
            goAndBack2: function () {
                var mpBxCkNumPx = $("#mpBx .holder").css("left");
                var mpBxCkNumMns = mpBxCkNumPx.split("px")[0];
                var mpBxCkNum = mpBxCkNumMns * -1
                var check = jQuery.inArray(mpBxCkNum, stopNum);
                if (check == -1) {
                    if (((mpBxNum + 1) < mpBxP) || ((mpBxNum + 1) > 1)) {
                        $("#mpBx .holder").animate({
                            left: (mpBxPgW * (mpBxNum * -1)) + "px"
                        }, {
                            duration: mpBxSpd
                        });
                    };
                };
            }
        };
        mpBxSldDg = function (whitch) {
            mpBxSldPs = $("#mpBx .sliderBtn").css("left").split("px")[0];
            mpBxNum = (mpBxSldPs / mpBxStpPx) * mpBxStp;
            $("#mpBx .pageNumber").html(~~ (mpBxNum + 1));
        };
        mpBxSldDg2 = function (whitch) {
            mpBxSldDg();
            $("#mpBx .holder").css({
                left: ((mpBxNum * mpBxPgW) * -1) + "px"
            });
        };
        hvChkItv = function () {
            if ((mpBxNum != 0) && (mpBxNum != (mpBxP - 1))) {} else if (mpBxNum != 0) {} else if (mpBxNum != (mpBxP - 1)) {
                $("#mpBx .sldBtnRight").css({
                    backgroundImage: "url(" + mpBxRtAwF.src + ")"
                });
            };
        };
        hvChkItv2 = function () {
            if (mpBxNum != (mpBxP - 1)) {
                $("#mpBx .fwd").css({
                    backgroundImage: "url(" + mpBxFwdF.src + ")"
                });
            } else {
                $("#mpBx .fwd").css({
                    backgroundImage: "url(" + mpBxFwd.src + ")"
                });
            }
        };
        hvChkItv3 = function () {
            if (mpBxNum != 0) {
                $("#mpBx .bwd").css({
                    backgroundImage: "url(" + mpBxBwdF.src + ")"
                });
            } else {
                $("#mpBx .bwd").css({
                    backgroundImage: "url(" + mpBxBwd.src + ")"
                });
            }
        };
    }
});

function limitChars(textid, infodiv, limit) {
    var text = $('#' + textid).val();
    var textlength = text.length;
    if (isNaN(limit)) {
        limit = 255;
    }
    if (textlength > limit) {
        $('#' + infodiv).html(limit + ' character limit reached.');
        $('#' + textid).val(text.substr(0, limit));
        return false;
    } else {
        $('#' + infodiv).html('You have ' + (limit - textlength) + ' characters left.');
        return true;
    }
}

function changeChars(textid, urldiv, ptn) {
    var t = $('#' + textid).val();
    var tlength = t.length - 1;
    var found = t.match(ptn);
    if (found != null) {
        if (found == " ") {
            $('#' + textid).val(t.replace(" ", "_"));
        } else {
            $('#' + textid).val(t.substr(0, tlength));
        }
        $('#' + urldiv).html('Invalid character!');
        return false;
    } else {
        $('#' + urldiv).html(' ');
        return true;
    }
}