var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
var apiUrl = '';
var commonRequestHeaders = {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
};
function setApiUrl(url) {
    apiUrl = url;
}
function sendHTTPRequest(userToken, body) {
    return __awaiter(this, void 0, void 0, function () {
        var options, repsonse;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    options = {
                        method: 'POST',
                        body: JSON.stringify(body),
                        headers: commonRequestHeaders,
                        credentials: 'include',
                    };
                    return [4, fetch(apiUrl + "&user_token=" + userToken, options)];
                case 1:
                    repsonse = _a.sent();
                    return [2, repsonse.json()];
            }
        });
    });
}
var FileSystemCheck = (function () {
    function FileSystemCheck(userToken) {
        var _this = this;
        this.userToken = '';
        this.fetchMainData = function (userToken) { return __awaiter(_this, void 0, void 0, function () {
            var _a, _b, status, _c, data;
            return __generator(this, function (_d) {
                switch (_d.label) {
                    case 0: return [4, sendHTTPRequest(this.userToken, { "action": "main" })];
                    case 1:
                        _a = _d.sent(), _b = _a.status, status = _b === void 0 ? 'error' : _b, _c = _a.data, data = _c === void 0 ? null : _c;
                        if (status.toLocaleLowerCase() === 'ok') {
                            return [2, data];
                        }
                        return [2, null];
                }
            });
        }); };
        this.fetchFileSystemCheckResults = function (userToken, path, excludedFiles, page) {
            if (page === void 0) { page = 1; }
            return __awaiter(_this, void 0, void 0, function () {
                var _a, _b, status, _c, data;
                return __generator(this, function (_d) {
                    switch (_d.label) {
                        case 0: return [4, sendHTTPRequest(this.userToken, {
                                action: "result",
                                path: path,
                                exclude: excludedFiles,
                                page: page
                            })];
                        case 1:
                            _a = _d.sent(), _b = _a.status, status = _b === void 0 ? 'error' : _b, _c = _a.data, data = _c === void 0 ? null : _c;
                            if (status.toLocaleLowerCase() === 'ok') {
                                return [2, data];
                            }
                            return [2, null];
                    }
                });
            });
        };
        this.userToken = userToken;
    }
    return FileSystemCheck;
}());
var UI = (function () {
    function UI(dictionary) {
        var _this = this;
        var _a;
        this.statisticPropertyTitleMap = {
            dir: 'fscheck_dir',
            files: 'fscheck_files',
            link: 'fscheck_sym_links',
            general: 'fscheck_checked_files'
        };
        this.dictionary = {};
        this.showPreloader = function () {
            var _a;
            (_a = document.querySelectorAll('div.preloader-container')) === null || _a === void 0 ? void 0 : _a.forEach(function (e) {
                e.style.display = '';
            });
        };
        this.hidePreloader = function () {
            var _a;
            (_a = document.querySelectorAll('div.preloader-container')) === null || _a === void 0 ? void 0 : _a.forEach(function (e) {
                e.style.display = 'none';
            });
        };
        this.showMain = function () {
            var mainBlock = document.getElementById('fileSystemCheckMain');
            var resultsBlock = document.getElementById('fileSystemCheckResult');
            if (mainBlock && resultsBlock) {
                mainBlock.style.display = '';
                resultsBlock.style.display = 'none';
            }
        };
        this.showResults = function () {
            var mainBlock = document.getElementById('fileSystemCheckMain');
            var resultsBlock = document.getElementById('fileSystemCheckResult');
            if (mainBlock && resultsBlock) {
                mainBlock.style.display = 'none';
                resultsBlock.style.display = '';
            }
        };
        this.fillPath = function (path) {
            var pathInputElement = document.getElementById('fscheckScanPath');
            if (pathInputElement) {
                pathInputElement.value = path;
            }
        };
        this.getPath = function () {
            var pathInputElement = document.getElementById('fscheckScanPath');
            if (pathInputElement) {
                return pathInputElement.value;
            }
            return null;
        };
        this.fillPagination = function (_a, callback) {
            var _b;
            var prev = _a.prev, next = _a.next, urls_pagination = _a.urls_pagination;
            var container = document.getElementById('paginationContainer');
            if (!container) {
                return;
            }
            (_b = container.querySelectorAll('a.ms-pagination__link')) === null || _b === void 0 ? void 0 : _b.forEach(function (e) { return e.removeEventListener('click', callback); });
            container.innerHTML = '';
            container.insertAdjacentHTML('beforeend', "<li class=\"ms-pagination__item ms-pagination__item-prev " + (prev === 'disabled' ? 'disabled' : '') + "\"></li>");
            container.lastChild.appendChild(_this.createPaginationLink('<', prev.toString(), callback));
            for (var _i = 0, _c = Object.entries(urls_pagination); _i < _c.length; _i++) {
                var _d = _c[_i], key = _d[0], value = _d[1];
                container.insertAdjacentHTML('beforeend', "<li class=\"ms-pagination__item " + (value === 'disabled' ? 'current' : '') + "\"></li>");
                container.lastChild.appendChild(_this.createPaginationLink(key.toString(), key.toString(), callback));
            }
            container.insertAdjacentHTML('beforeend', "<li class=\"ms-pagination__item ms-pagination__item-next " + (next === 'disabled' ? 'disabled' : '') + "\"></li>");
            container.lastChild.appendChild(_this.createPaginationLink('>', next.toString(), callback));
        };
        this.fillCheckResults = function (_a) {
            var general = _a.result.general;
            var container = document.getElementById('checkResultComtainer');
            if (!container) {
                return;
            }
            container.innerHTML = '';
            for (var _i = 0, _b = Object.entries(general); _i < _b.length; _i++) {
                var _c = _b[_i], key = _c[0], value = _c[1];
                container.insertAdjacentHTML('beforeend', "<tr>\n                    <td>" + key + "</td>\n                    <td class=\"text-center\">\n                        <span class=\"ms-tag ms-tag-danger\">" + value + "</span>\n                    </td>\n                </tr>");
            }
            _this.showResults();
        };
        this.fillStatistic = function (dictionary, statistic) {
            var statisticTableBody = document.getElementById('statisticTableBody');
            if (!statisticTableBody) {
                return;
            }
            statisticTableBody.innerHTML = '';
            for (var _i = 0, _a = Object.entries(statistic); _i < _a.length; _i++) {
                var _b = _a[_i], key = _b[0], value = _b[1];
                var title = dictionary[_this.statisticPropertyTitleMap[key]];
                if (title) {
                    statisticTableBody.insertAdjacentHTML('beforeend', "<div class=\"swiper-slide swiper-slide-active\" role=\"group\">\n                        <div class=\"ms-page-cards-slider__item\">\n                            <span class=\"ms-page-cards-slider__number\">\n                                " + value + "\n                            </span>\n                            <span class=\"ms-page-cards-slider__title\">\n                                " + title + "\n                            </span>\n                        </div>\n                    </div>");
                }
            }
        };
        this.showNewVersionAlert = function (html) {
            var newVersionAlert = document.getElementById('newVersionAlert');
            if (newVersionAlert && html) {
                newVersionAlert.innerHTML = html;
                newVersionAlert.style.display = '';
            }
        };
        this.createPaginationLink = function (innerText, pageNum, callback) {
            var a = document.createElement('a');
            a.classList.add('ms-pagination__link');
            a.insertAdjacentHTML('afterbegin', innerText);
            a.dataset.page = pageNum;
            a.addEventListener('click', callback);
            return a;
        };
        this.addToggleEventListener = function (control, toggledElement) {
            control.addEventListener('click', function () {
                control.classList.toggle('active');
                toggledElement.style.display = control.classList.contains('active') ? 'block' : 'none';
            });
        };
        this.dictionary = dictionary;
        for (var _i = 0, _b = Array.from(document.querySelectorAll('[data-translation-key]')); _i < _b.length; _i++) {
            var element = _b[_i];
            element.innerText = dictionary[element.dataset.translationKey];
        }
        for (var _c = 0, _d = Array.from(document.querySelectorAll('[data-toggled-element-id]')); _c < _d.length; _c++) {
            var element = _d[_c];
            var toggledElement = document.getElementById(element.dataset.toggledElementId);
            if (toggledElement) {
                this.addToggleEventListener(element, toggledElement);
            }
        }
        var backButton = document.getElementById('fileSystemCheckResultBackButton');
        backButton === null || backButton === void 0 ? void 0 : backButton.addEventListener('click', this.showMain);
        (_a = document.querySelectorAll('a.ms-tooltip')) === null || _a === void 0 ? void 0 : _a.forEach(function (e) {
            var tooltipContainer = e.querySelector('span.tooltip-container');
            e.addEventListener('mouseenter', function () { return tooltipContainer.classList.add('tooltip-visible'); });
            e.addEventListener('mouseleave', function () { return tooltipContainer.classList.remove('tooltip-visible'); });
        });
    }
    return UI;
}());
function main() {
    var _a, _b, _c, _d;
    return __awaiter(this, void 0, void 0, function () {
        var userToken, dictionary, ui, fileSystemCheck, _e, path, result, paginationCallback;
        var _this = this;
        return __generator(this, function (_f) {
            switch (_f.label) {
                case 0:
                    userToken = (_a = document.getElementById('user_token')) === null || _a === void 0 ? void 0 : _a.value;
                    setApiUrl((_b = document.getElementById('api_url')) === null || _b === void 0 ? void 0 : _b.value);
                    dictionary = JSON.parse(((_c = document.getElementById('messor-plugin-dictionary')) === null || _c === void 0 ? void 0 : _c.innerText) || "{}");
                    ui = new UI(dictionary);
                    if (!userToken) {
                        throw new Error('User token not found');
                    }
                    fileSystemCheck = new FileSystemCheck(userToken);
                    return [4, fileSystemCheck.fetchMainData(userToken)];
                case 1:
                    _e = _f.sent(), path = _e.path, result = _e.result;
                    ui.fillPath(path);
                    ui.hidePreloader();
                    paginationCallback = function (_a) {
                        var target = _a.target;
                        return __awaiter(_this, void 0, void 0, function () {
                            var page, data;
                            var _b;
                            return __generator(this, function (_c) {
                                switch (_c.label) {
                                    case 0:
                                        page = Number(target.dataset.page);
                                        if (!page) {
                                            return [2];
                                        }
                                        ui.showPreloader();
                                        return [4, fileSystemCheck.fetchFileSystemCheckResults(userToken, ui.getPath(), ((_b = document.getElementById('excludedFiles')) === null || _b === void 0 ? void 0 : _b.value) || '', page)];
                                    case 1:
                                        data = _c.sent();
                                        if (!data) {
                                            ui.hidePreloader();
                                            return [2];
                                        }
                                        ui.fillPagination(data, paginationCallback);
                                        ui.fillCheckResults(data);
                                        ui.hidePreloader();
                                        return [2];
                                }
                            });
                        });
                    };
                    (_d = document.getElementById('fscheckScan')) === null || _d === void 0 ? void 0 : _d.addEventListener('click', function () { return __awaiter(_this, void 0, void 0, function () {
                        var data;
                        var _a;
                        return __generator(this, function (_b) {
                            switch (_b.label) {
                                case 0:
                                    ui.showPreloader();
                                    return [4, fileSystemCheck.fetchFileSystemCheckResults(userToken, ui.getPath(), ((_a = document.getElementById('excludedFiles')) === null || _a === void 0 ? void 0 : _a.value) || '')];
                                case 1:
                                    data = _b.sent();
                                    if (!data) {
                                        ui.hidePreloader();
                                        return [2];
                                    }
                                    ui.fillStatistic(dictionary, data.statistic);
                                    ui.fillPagination(data, paginationCallback);
                                    ui.fillCheckResults(data);
                                    ui.hidePreloader();
                                    return [2];
                            }
                        });
                    }); });
                    return [2];
            }
        });
    });
}
main();
