define([
    'jquery',
    'underscore',
    'backbone',
    'handlebars',
    'text!templates/topic/create.html'
], function($, _, Backbone, Handlebars, template){
    return Backbone.View.extend({
        id: '#createTopic',
        render: function(){
            console.log('render: topic/create');
            var ctemplate = Handlebars.compile(template);
            var data = {};
            this.$el.append(ctemplate(data));
            $(document.body).append(this.$el);
            var self = this;
            this.$el.find('.modal').modal().on('hidden', function(){
                self.$el.remove();
            });
            return this;
        }
    });
});
