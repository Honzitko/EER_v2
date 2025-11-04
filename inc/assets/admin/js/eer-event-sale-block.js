(function (wp, eerData) {
        if (!wp || !wp.blocks) {
                return;
        }

        var registerBlockType = wp.blocks.registerBlockType;
        var __ = wp.i18n.__;
        var components = wp.components;
        var blockEditor = wp.blockEditor || wp.editor;

        var InspectorControls = blockEditor ? blockEditor.InspectorControls : wp.editor.InspectorControls;
        var BlockControls = blockEditor ? blockEditor.BlockControls : wp.editor.BlockControls;
        var AlignmentToolbar = blockEditor ? blockEditor.AlignmentToolbar : wp.editor.AlignmentToolbar;
        var RichText = blockEditor ? blockEditor.RichText : wp.editor.RichText;
        var PanelColorSettings = blockEditor ? blockEditor.PanelColorSettings : wp.editor.PanelColorSettings;
        var useBlockProps = blockEditor && blockEditor.useBlockProps ? blockEditor.useBlockProps : function () {
                return { className: 'eer-event-sale-block-editor' };
        };

        var PanelBody = components.PanelBody;
        var SelectControl = components.SelectControl;
        var ToggleControl = components.ToggleControl;
        var RangeControl = components.RangeControl;
        var Placeholder = components.Placeholder;
        var Notice = components.Notice;
        var Fragment = wp.element.Fragment;

        var events = Array.isArray(eerData && eerData.events) ? eerData.events : [];
        var noEventsLabel = eerData && eerData.noEventsLabel ? eerData.noEventsLabel : __('No events available.', 'easy-event-registration');
        var selectEventLabel = eerData && eerData.selectEvent ? eerData.selectEvent : __('Select an event', 'easy-event-registration');
        var defaultIntro = eerData && eerData.defaultIntro ? eerData.defaultIntro : '';

        registerBlockType('easy-event-registration/event-sale', {
                apiVersion: 2,
                title: __('Easy Event Registration', 'easy-event-registration'),
                description: __('Embed an Easy Event Registration sale form that is fully customizable.', 'easy-event-registration'),
                icon: 'calendar-alt',
                category: 'widgets',
                attributes: {
                        eventId: {
                                type: 'number',
                                default: 0
                        },
                        align: {
                                type: 'string',
                                default: ''
                        },
                        showTitle: {
                                type: 'boolean',
                                default: true
                        },
                        customTitle: {
                                type: 'string',
                                default: ''
                        },
                        showEventId: {
                                type: 'boolean',
                                default: false
                        },
                        introText: {
                                type: 'string',
                                default: ''
                        },
                        backgroundColor: {
                                type: 'string',
                                default: ''
                        },
                        textColor: {
                                type: 'string',
                                default: ''
                        },
                        padding: {
                                type: 'number',
                                default: 24
                        },
                        borderRadius: {
                                type: 'number',
                                default: 0
                        },
                        showBorder: {
                                type: 'boolean',
                                default: false
                        },
                        borderColor: {
                                type: 'string',
                                default: '#dcdcde'
                        },
                        borderWidth: {
                                type: 'number',
                                default: 1
                        },
                        shadow: {
                                type: 'boolean',
                                default: false
                        }
                },
                supports: {
                        align: ['wide', 'full'],
                        customClassName: true,
                        html: false
                },
                edit: function (props) {
                        var attributes = props.attributes;
                        var setAttributes = props.setAttributes;

                        var blockProps = useBlockProps({
                                className: ['eer-event-sale-block-editor', attributes.shadow ? 'has-shadow' : ''].join(' ').trim(),
                                style: buildPreviewStyle(attributes)
                        });

                        var selectedEvent = findSelectedEvent(events, attributes.eventId);
                        var hasEvents = events.length > 0;

                        var alignmentControls = wp.element.createElement(BlockControls, {},
                                wp.element.createElement(AlignmentToolbar, {
                                        value: attributes.align,
                                        onChange: function (value) {
                                                setAttributes({ align: value });
                                        }
                                })
                        );

                        var inspector = wp.element.createElement(InspectorControls, {},
                                wp.element.createElement(PanelBody, {
                                        title: __('Event settings', 'easy-event-registration'),
                                        initialOpen: true
                                },
                                        hasEvents ? wp.element.createElement(SelectControl, {
                                                label: __('Event', 'easy-event-registration'),
                                                value: attributes.eventId ? String(attributes.eventId) : '',
                                                options: buildEventOptions(events, selectEventLabel),
                                                onChange: function (value) {
                                                        setAttributes({ eventId: value ? parseInt(value, 10) : 0 });
                                                }
                                        }) : wp.element.createElement(Notice, {
                                                status: 'info',
                                                isDismissible: false
                                        }, noEventsLabel)
                                ),
                                wp.element.createElement(PanelBody, {
                                        title: __('Display options', 'easy-event-registration'),
                                        initialOpen: false
                                },
                                        wp.element.createElement(ToggleControl, {
                                                label: __('Show title', 'easy-event-registration'),
                                                checked: !!attributes.showTitle,
                                                onChange: function (value) {
                                                        setAttributes({ showTitle: value });
                                                }
                                        }),
                                        wp.element.createElement(ToggleControl, {
                                                label: __('Show event ID badge', 'easy-event-registration'),
                                                checked: !!attributes.showEventId,
                                                onChange: function (value) {
                                                        setAttributes({ showEventId: value });
                                                }
                                        }),
                                        wp.element.createElement(ToggleControl, {
                                                label: __('Add border', 'easy-event-registration'),
                                                checked: !!attributes.showBorder,
                                                onChange: function (value) {
                                                        setAttributes({ showBorder: value });
                                                }
                                        }),
                                        !!attributes.showBorder && wp.element.createElement(RangeControl, {
                                                label: __('Border width (px)', 'easy-event-registration'),
                                                value: attributes.borderWidth,
                                                min: 1,
                                                max: 12,
                                                onChange: function (value) {
                                                        setAttributes({ borderWidth: value || 1 });
                                                }
                                        }),
                                        wp.element.createElement(ToggleControl, {
                                                label: __('Add drop shadow', 'easy-event-registration'),
                                                checked: !!attributes.shadow,
                                                onChange: function (value) {
                                                        setAttributes({ shadow: value });
                                                }
                                        }),
                                        wp.element.createElement(RangeControl, {
                                                label: __('Padding (px)', 'easy-event-registration'),
                                                value: attributes.padding,
                                                min: 0,
                                                max: 120,
                                                onChange: function (value) {
                                                        setAttributes({ padding: typeof value === 'number' ? value : 0 });
                                                }
                                        }),
                                        wp.element.createElement(RangeControl, {
                                                label: __('Corner radius (px)', 'easy-event-registration'),
                                                value: attributes.borderRadius,
                                                min: 0,
                                                max: 64,
                                                onChange: function (value) {
                                                        setAttributes({ borderRadius: typeof value === 'number' ? value : 0 });
                                                }
                                        })
                                ),
                                wp.element.createElement(PanelColorSettings, {
                                        title: __('Color settings', 'easy-event-registration'),
                                        colorSettings: [
                                                {
                                                        label: __('Background color', 'easy-event-registration'),
                                                        onChange: function (value) {
                                                                setAttributes({ backgroundColor: value || '' });
                                                        },
                                                        value: attributes.backgroundColor
                                                },
                                                {
                                                        label: __('Text color', 'easy-event-registration'),
                                                        onChange: function (value) {
                                                                setAttributes({ textColor: value || '' });
                                                        },
                                                        value: attributes.textColor
                                                },
                                                !!attributes.showBorder && {
                                                        label: __('Border color', 'easy-event-registration'),
                                                        onChange: function (value) {
                                                                setAttributes({ borderColor: value || '#dcdcde' });
                                                        },
                                                        value: attributes.borderColor || '#dcdcde'
                                                }
                                        ].filter(Boolean)
                                })
                        );

                        if (!hasEvents) {
                                return wp.element.createElement(Fragment, {}, inspector,
                                        wp.element.createElement(Placeholder, {
                                                icon: 'calendar-alt',
                                                label: __('Easy Event Registration', 'easy-event-registration'),
                                                instructions: noEventsLabel
                                        })
                                );
                        }

                        var headerContent = null;
                        if (attributes.showTitle) {
                                headerContent = wp.element.createElement('div', { className: 'eer-event-sale-block__header' },
                                        wp.element.createElement(RichText, {
                                                tagName: 'h3',
                                                className: 'eer-event-sale-block__title',
                                                value: attributes.customTitle,
                                                allowedFormats: [],
                                                onChange: function (value) {
                                                        setAttributes({ customTitle: value });
                                                },
                                                placeholder: selectedEvent ? selectedEvent.label : __('Event title', 'easy-event-registration')
                                        }),
                                        attributes.showEventId && attributes.eventId ? wp.element.createElement('span', { className: 'eer-event-sale-block__event-id' }, '#' + attributes.eventId) : null
                                );
                        } else if (attributes.showEventId && attributes.eventId) {
                                headerContent = wp.element.createElement('div', { className: 'eer-event-sale-block__header' },
                                        wp.element.createElement('span', { className: 'eer-event-sale-block__event-id' }, '#' + attributes.eventId)
                                );
                        }

                        return wp.element.createElement(Fragment, {}, alignmentControls, inspector,
                                wp.element.createElement('div', blockProps,
                                        headerContent,
                                        wp.element.createElement(RichText, {
                                                tagName: 'p',
                                                className: 'eer-event-sale-block__intro',
                                                value: attributes.introText,
                                                placeholder: defaultIntro,
                                                onChange: function (value) {
                                                        setAttributes({ introText: value });
                                                }
                                        }),
                                        wp.element.createElement('div', { className: 'eer-event-sale-block__content eer-event-sale-block__content--preview' },
                                                attributes.eventId ? wp.element.createElement('p', {}, __('The registration form for this event will be displayed on the front-end.', 'easy-event-registration')) : wp.element.createElement('p', {}, __('Select an event from the block settings to preview it here.', 'easy-event-registration')),
                                                attributes.eventId && selectedEvent ? wp.element.createElement('p', { className: 'eer-event-sale-block__selected-event' }, __('Event ID:', 'easy-event-registration') + ' ' + attributes.eventId + ' · ' + selectedEvent.label) : null
                                        )
                                )
                        );
                },
                save: function () {
                        return null;
                }
        });

        function buildEventOptions(events, selectEventLabel) {
                var options = [{
                        label: selectEventLabel,
                        value: ''
                }];

                events.forEach(function (eventItem) {
                        options.push({
                                label: eventItem.label,
                                value: String(eventItem.value)
                        });
                });

                return options;
        }

        function findSelectedEvent(events, eventId) {
                        if (!eventId) {
                                return null;
                        }

                        var match = null;
                        events.forEach(function (eventItem) {
                                if (parseInt(eventItem.value, 10) === parseInt(eventId, 10)) {
                                        match = eventItem;
                                }
                        });

                        return match;
        }

        function buildPreviewStyle(attributes) {
                var style = {};
                if (attributes.backgroundColor) {
                        style.backgroundColor = attributes.backgroundColor;
                }
                if (attributes.textColor) {
                        style.color = attributes.textColor;
                }
                if (typeof attributes.padding === 'number') {
                        style.padding = attributes.padding + 'px';
                }
                if (typeof attributes.borderRadius === 'number') {
                        style.borderRadius = attributes.borderRadius + 'px';
                }
                if (attributes.showBorder) {
                        style.border = (attributes.borderWidth || 1) + 'px solid ' + (attributes.borderColor || '#dcdcde');
                }
                if (attributes.shadow) {
                        style.boxShadow = '0 20px 50px rgba(0,0,0,0.1)';
                }
                return style;
        }
})(window.wp || {}, window.EEREventBlockData || {});
