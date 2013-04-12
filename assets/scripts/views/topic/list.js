define([
    'jquery',
    'underscore',
    'backbone',
    'handlebars',
    'text!templates/topic/list.html'
], function($, _, Backbone, Handlebars, template){
    return Backbone.View.extend({
        el: $('#container'),
        initialize: function(){
            this.listenTo(this.collection, 'change', this.render);
        },
        render: function(){
            var ctemplate = Handlebars.compile(template);
            var data = {
                node: this.collection.node,
                user: App.user.toJSON()
            };
            this.$el.html(ctemplate(data));
            this.setActive(this.collection.filter);
            this.renderTopics();
            return this;
        },
        setActive: function(filter){
            this.$('.' + filter).addClass('active');
        },
        renderTopics: function(){
            var self = this;
            require([
                'views/topic/item'
            ], function(View){
                self.collection.fetch({
                    success: function(topics){
                        topics.each(function(topic){
                            var view = new View({
                                el: this.$('.main .topics'),
                                model: topic 
                            });
                            view.render();
                        });
                    }
                });
            });
        }
    });
});