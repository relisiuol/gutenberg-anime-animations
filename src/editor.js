/**
 * Ajoute un attribut et un panneau Anime.js à chaque bloc.
 */
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { defaultI18n } from '@wordpress/i18n';

// 1) Attribut supplémentaire
const addAttr = ( settings ) => {
    if ( settings.attributes ) {
        settings.attributes.gaaAnimation = { type: 'string', default: '' };
    }
    return settings;
};
addFilter( 'blocks.registerBlockType', 'gaa/add-attr', addAttr );

// 2) Panneau latéral
const withPanel = createHigherOrderComponent(
    ( BlockEdit ) => ( props ) => {
        const { attributes: { gaaAnimation }, setAttributes } = props;
        return (
            <>
                <BlockEdit { ...props } />
                <InspectorControls>
                    <PanelBody
                        title={ defaultI18n.__('Animation Anime.js', 'gutenberg-anime-animations') }
                        icon="video-alt3"
                        initialOpen={ false }
                    >
                        <SelectControl
                            label="Effet"
                            value={ gaaAnimation }
                            options={ [
                                { label: defaultI18n.__('None', 'gutenberg-anime-animations'),   value: '' },
                                { label: defaultI18n.__('Fade in', 'gutenberg-anime-animations'),  value: 'fadeIn' },
                                { label: defaultI18n.__('Slide up', 'gutenberg-anime-animations'), value: 'slideUp' },
                                { label: defaultI18n.__('Scale in', 'gutenberg-anime-animations'), value: 'scaleIn' },
                            ] }
                            onChange={ ( v ) => setAttributes( { gaaAnimation: v } ) }
                        />
                    </PanelBody>
                </InspectorControls>
            </>
        );
    },
    'withPanel'
);
addFilter( 'editor.BlockEdit', 'gaa/panel', withPanel );

// 3) Ajout data attribute dans le markup
const addProp = ( extraProps, _blockType, attrs ) => {
    if ( attrs.gaaAnimation ) {
        return { ...extraProps, 'data-gaa-animation': attrs.gaaAnimation };
    }
    return extraProps;
};
addFilter( 'blocks.getSaveContent.extraProps', 'gaa/prop', addProp );
