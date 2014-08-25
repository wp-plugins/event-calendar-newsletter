var ecn = ecn || {};

(function($) {
    $.fn.insertAtCaret = function(text) {
        return this.each(function() {
            if (document.selection && this.tagName == 'TEXTAREA') {
                //IE textarea support
                this.focus();
                sel = document.selection.createRange();
                sel.text = text;
                this.focus();
            } else if (this.selectionStart || this.selectionStart == '0') {
                //MOZILLA/NETSCAPE support
                startPos = this.selectionStart;
                endPos = this.selectionEnd;
                scrollTop = this.scrollTop;
                this.value = this.value.substring(0, startPos) + text + this.value.substring(endPos, this.value.length);
                this.focus();
                this.selectionStart = startPos + text.length;
                this.selectionEnd = startPos + text.length;
                this.scrollTop = scrollTop;
            } else {
                // IE input[type=text] and other browsers
                this.value += text;
                this.focus();
                this.value = this.value;    // forces cursor to end
            }
        });
    };

    ecn.AdminView = Backbone.View.extend({
        el: '#ecn-admin',

        initialize: function() {
            this.$('.result').hide();
            this.$('.loading').hide();
            var me = this;
            $(document).ajaxStart(function() {
                me.$('.loading').show();
            });
            $(document).ajaxStop(function() {
                me.$('.loading').hide();
            });
        },

        events: {
            'click #fetch_events': 'fetchEvents',
            'click #insert_placeholder': 'insertPlaceholder'
        },

        insertPlaceholder: function(event) {
            event.preventDefault();
            var placeholder = this.$('#placeholder').val();
            this.$('textarea[name="format"]').insertAtCaret( '{' + placeholder + '}' );
        },

        fetchEvents: function(event) {
            var me = this;
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                dataType: 'json',
                data: {
                    action: 'fetch_events',
                    nonce: me.$('#wp_ecn_admin_nonce').val(),
                    event_calendar: me.$('select[name="event_calendar"]').val(),
                    events_future_in_months: me.$('select[name="events_future_in_months"]').val(),
                    format: me.$('textarea[name="format"]').val()
                },
                success: function(result) {
                    me.$('.result').show();
                    me.$('#output_html').val(result.result);
                    me.$('#output').html(result.result);
                },
                error: function(v, msg) {
                    alert(msg);
                }
            });
        }
    });

    $(document).ready(function() {
        ecn.adminView = new ecn.AdminView();
    });
})(jQuery);