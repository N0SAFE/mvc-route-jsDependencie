export const module = {
    moduleArray: [],
    add: function(moduleScript, moduleName) {
        this.moduleArray.push({
            name: moduleName,
            script: moduleScript
        })
    },
    getAll: function() {
        return this.moduleArray.map(function(module) { return module.script })
    },
    get: function(moduleName) {
        return this.moduleArray.find(function(item) {
            return item.name == moduleName
        }).script
    }
}