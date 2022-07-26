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
function sendHTTPRequest(url, body) {
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
                    return [4, fetch(url, options)];
                case 1:
                    repsonse = _a.sent();
                    return [2, repsonse.json()];
            }
        });
    });
}
function fetchPageData(userToken) {
    return __awaiter(this, void 0, void 0, function () {
        var _a, _b, status, _c, data;
        return __generator(this, function (_d) {
            switch (_d.label) {
                case 0: return [4, sendHTTPRequest(apiUrl + "&user_token=" + userToken, { "action": "main" })];
                case 1:
                    _a = _d.sent(), _b = _a.status, status = _b === void 0 ? 'error' : _b, _c = _a.data, data = _c === void 0 ? null : _c;
                    if (status.toLocaleLowerCase() === 'ok') {
                        data['prefix_or_show_error'] = data['prefix'] || data['show_error'];
                        data['new_version_messor_has'] = Boolean(data['new_version_messor']);
                        return [2, data];
                    }
                    return [2, null];
            }
        });
    });
}
function createPermissionTableRow(path, permission) {
    var tr = document.createElement('tr');
    tr.insertAdjacentHTML('afterbegin', "<td>" + path + "</td><td class=\"light-bg text-center\">" + permission + "</td>");
    return tr;
}
function createTagCheck(error) {
    var span = document.createElement('span');
    span.classList.add("ms-tag-" + (error ? 'cross' : 'check'));
    return span;
}
function createUserAccountTableRow(userData, dictionary) {
    var tr = document.createElement('tr');
    tr.insertAdjacentHTML('afterbegin', "<td>" + userData.group + "</td>\n        <td class=\"light-bg\">\n            " + (userData.novalid
        ? "<span class=\"ms-tag-trust ms-tag-trust-10\">" + userData.login + "</span>"
        : userData.login) + "\n        </td>\n        <td>" + userData.firstname + " " + userData.lastname + "</td>");
    tr.insertAdjacentHTML('beforeend', "<td class=\"text-center\">\n            <a\n                data-has-translation=\"true\"\n                href=\"" + userData.edit + "\"\n                class=\"ms-link-primary\">\n                " + dictionary['ss_change_login_edit'] + "\n            </a>\n        </td>");
    return tr;
}
function main() {
    var _a, _b, _c, _d, _e, _f, _g;
    return __awaiter(this, void 0, void 0, function () {
        var userToken, dictionary, _i, _h, element, key, data, pathsPermission, _j, _k, _l, path, permission, _m, _o, _p, key, value, element, userAccountsElement, _q, _r, userData, _s, _t, element, key, _u, _v, element, dependencyKey, newVersionAlert;
        return __generator(this, function (_w) {
            switch (_w.label) {
                case 0:
                    userToken = (_a = document.getElementById('user_token')) === null || _a === void 0 ? void 0 : _a.value;
                    apiUrl = (_b = document.getElementById('api_url')) === null || _b === void 0 ? void 0 : _b.value;
                    dictionary = JSON.parse(((_c = document.getElementById('messor-plugin-dictionary')) === null || _c === void 0 ? void 0 : _c.innerText) || "{}");
                    for (_i = 0, _h = Array.from(document.querySelectorAll('[data-has-translation="true"]')); _i < _h.length; _i++) {
                        element = _h[_i];
                        key = element.innerText.trim();
                        element.innerText = dictionary[key];
                    }
                    if (!userToken) {
                        throw new Error('User token not found');
                    }
                    return [4, fetchPageData(userToken)];
                case 1:
                    data = _w.sent();
                    if (!data) {
                        throw new Error('Failed to fetch page data');
                    }
                    pathsPermission = document.getElementById('pathsPermission');
                    if (pathsPermission) {
                        for (_j = 0, _k = Object.entries(data.perms); _j < _k.length; _j++) {
                            _l = _k[_j], path = _l[0], permission = _l[1];
                            pathsPermission.appendChild(createPermissionTableRow(path, permission));
                        }
                    }
                    for (_m = 0, _o = Object.entries(data); _m < _o.length; _m++) {
                        _p = _o[_m], key = _p[0], value = _p[1];
                        if (typeof value !== 'boolean') {
                            continue;
                        }
                        element = document.getElementById(key + "_SecurityCheck");
                        if (!element) {
                            continue;
                        }
                        element.appendChild(createTagCheck(value));
                    }
                    userAccountsElement = document.getElementById('userAccounts');
                    if (userAccountsElement) {
                        for (_q = 0, _r = data.user; _q < _r.length; _q++) {
                            userData = _r[_q];
                            userAccountsElement.appendChild(createUserAccountTableRow(userData, dictionary));
                        }
                    }
                    for (_s = 0, _t = Array.from(document.querySelectorAll('[data-has-value="true"]')); _s < _t.length; _s++) {
                        element = _t[_s];
                        key = element.innerHTML;
                        element.innerHTML = (_d = data[key]) !== null && _d !== void 0 ? _d : '';
                    }
                    for (_u = 0, _v = Array.from(document.querySelectorAll('[data-has-dependency]')); _u < _v.length; _u++) {
                        element = _v[_u];
                        dependencyKey = element.dataset['hasDependency'];
                        if (dependencyKey.startsWith('!')) {
                            element.style.display = !(data[dependencyKey.substring(1)]) ? '' : 'none';
                            continue;
                        }
                        element.style.display = (data[dependencyKey]) ? '' : 'none';
                    }
                    (_e = document.querySelectorAll('a.ms-tooltip')) === null || _e === void 0 ? void 0 : _e.forEach(function (e) {
                        var tooltipContainer = e.querySelector('span.tooltip-container');
                        e.addEventListener('mouseenter', function () { return tooltipContainer.classList.add('tooltip-visible'); });
                        e.addEventListener('mouseleave', function () { return tooltipContainer.classList.remove('tooltip-visible'); });
                    });
                    (_f = document.querySelector('svg.circular-chart > path.circle')) === null || _f === void 0 ? void 0 : _f.setAttribute('stroke-dasharray', (data.percent || 0) + ", 100");
                    newVersionAlert = document.getElementById('newVersionAlert');
                    if (newVersionAlert && data.new_version_messor) {
                        newVersionAlert.innerHTML = data.new_version_messor;
                        newVersionAlert.style.display = '';
                    }
                    (_g = document.querySelectorAll('div.preloader-container')) === null || _g === void 0 ? void 0 : _g.forEach(function (e) {
                        e.style.display = 'none';
                    });
                    return [2];
            }
        });
    });
}
main();
