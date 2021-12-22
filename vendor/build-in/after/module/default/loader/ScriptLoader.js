import { importScript } from "../../../tool/default/function/function.js"
// util
class ScriptLoader {
    loaded = {}
    baseLocation = ""
    event = { load: [], call: [] }

    constructor(baseLocation) {
        this.importScript = importScript
        this.baseLocation = baseLocation.length == 0 || (baseLocation.substring(baseLocation.length - 1) == "/" || baseLocation.substring(baseLocation.length - 1) == "\\") ? baseLocation : baseLocation + "/"
    }

    // callback is : callback(object:Object<module:Object, ini func:Object, sort:string>)
    addListener(event, callback) {
        if (event == "load") {
            this.event.load.push(callback);
        } else if (event == "call") {
            this.event.call.push(callback);
        }
    }

    getLoaded() {
        return this.loaded
    }
    getLoadedBySort(sort = undefined) {
        return Array.from(Object.entries(this.loaded).filter(function([path, object]) {
            if (typeof object.sort == "string" && object.sort.includes(sort)) {
                return true
            }
        }).map(function([path, object]) {
            let obj = {...object }
            obj.path = path
            return obj
        }))
    }

    setBaseLocation(baseLocation) {
        this.baseLocation = baseLocation.length == 0 || (baseLocation.substring(baseLocation.length - 1) == "/" || baseLocation.substring(baseLocation.length - 1) == "\\") ? baseLocation : baseLocation + "/"
    }

    // template async load(href:string, iniFunc:?string, args:?Array<string>||string, sort:?string, params:?Object<inner:bool>)
    async load(href, iniFunc, args, sort, params) {
        let name

        if (href == undefined) {
            return false;
        }

        params = params || {};

        if (href.substring(0, 2) != "./") {
            params.inner = true
            name = href
            let notValid = true

            let namespace = name.split("/")[0]
            let tempNamespace

            Object.entries(ns.json.loader).every(function([typeLoadName, typeLoadObj]) {

                tempNamespace = "default"
                if (typeLoadObj[namespace] != undefined) {
                    tempNamespace = namespace
                }

                if (typeLoadObj[tempNamespace][name] != undefined) {
                    notValid = false
                    if (tempNamespace != "default") {
                        name = tempNamespace + "/" + name
                    }
                    href = typeLoadName + "/" + tempNamespace + "/" + typeLoadObj[tempNamespace][name].path
                }

                return notValid
            })

            if (notValid) {
                href = "../../public/" + name
            }

        } else {
            if (params.name == undefined) {
                name = "project/" + href.split("/")[href.split("/").length - 2] + "/" + href.split("/")[href.split("/").length - 1]
            } else {
                name = params.name
            }
            href = '../../../public/' + this.baseLocation + href
        }

        if (typeof sort !== 'string' && !(sort instanceof String)) {
            sort = undefined
        }

        if (!Array.isArray(args)) { args = args == null ? [] : [args] }
        if (this.isLoaded(name)) {
            return this.loaded[name].module;
        }

        console.log(href)
        let module = await this.importScript(href);
        if (module == undefined) { console.error(new URL(sessionStorage.baseURL + href).href) }

        if (iniFunc) {
            try {
                await module[iniFunc](...[...args, scriptLoader]);
            } catch (e) {
                try {
                    await module[iniFunc]
                    console.error(module, "in [" + new URL(sessionStorage.baseURL + href).href + "] do not provide an export called {" + iniFunc + "} or the function called {" + iniFunc + "} throw an error")
                    console.error(e)
                } catch {
                    console.error(module, "in [" + new URL(sessionStorage.baseURL + href).href + "] not exist")
                }
            }
        }
        this.loaded[name] = { "module": module, "ini func": iniFunc != null ? { "function": { "name": iniFunc, "callback": module[iniFunc] }, "args": args.length != 0 ? args : null } : null, "sort": sort, "href": href };

        // event
        this.event.load.forEach(function(callback) { callback(this.loaded[name]) })
        return module;
    }

    // param async loads(...args?:Array<href:string, iniFunc:?string, args:?Array<string>||string, sort:?string, ...params:?any>)
    async loads(...args) {
        let modules = await Promise.all(await args.asyncForEach(async function([href, iniFunc, args, sort, params]) {
            return this.load(href, iniFunc, args, sort, params)
        }.bind(this), false))
        return modules;
    }

    sortIn(module, sortName) {
        if (function(toTest, ...args) { for (let arg of args) { if (toTest === arg) return true } return false }(sortName, ...["sortIn", "load", "constructor", "call", "getSort", "isLoaded"]))
            return false;
        this[sortName].push(module);
    }

    getSort(sortName) {
        return this[sortName]
    }

    call(href, functionCalled = null) {
        if (!this.isLoaded(href)) {
            return false
        }

        href = Object.values(this.loaded).find(function(object) {
            return object.href == href
        }) == undefined ? href : Object.values(this.loaded).find(function(object) {
            return object.href == href
        }).module

        if (functionCalled) {
            return this.loaded[href].module[functionCalled]
        }

        // event
        this.event.call.forEach(function(callback) { callback(this.loaded[href]) })
        return this.loaded[href].module;
    }

    isLoaded(href) {
        if (Object.keys(this.loaded).indexOf(href) == -1) {
            return false
        }
        return true
    }
}


let scriptLoader;

export function setScriptLoader(baseLocation = "") {
    scriptLoader = new ScriptLoader(baseLocation);
}
export default function getScriptLoader() {
    return scriptLoader;
}