export const tool = {
    toolArray: [],
    add: function(toolScript, toolName) {
        this.toolArray.push({
            name: toolName,
            script: toolScript
        })
    },
    getAll: function() {
        return this.toolArray.map(function(tool) { return tool.script })
    },
    get: function(toolName) {
        return this.toolArray.find(function(item) {
            return item.name == toolName
        }).script
    }
}