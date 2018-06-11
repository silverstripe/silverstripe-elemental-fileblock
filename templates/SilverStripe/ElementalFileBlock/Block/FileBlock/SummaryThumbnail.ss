<% if $Image %>
    <span class="elemental-preview__thumbnail-image">
        <% if $IsPlaceholder %>
            <img
                alt="<%t SilverStripe\\ElementalFileBlock\\Block\\FileBlock.BlockType 'File' %>"
                src="{$Image.ATT}"
                class="elemental-preview__thumbnail-image--placeholder" />
        <% else %>
            $Image
        <% end_if %>
    </span>
<% end_if %>
