Feature: Use elemental fileblock
  As a website user
  I want to use elemental fileblock

  Background:
    Given I add an extension "DNADesign\Elemental\Extensions\ElementalPageExtension" to the "Page" class
    And the "group" "EDITOR" has permissions "Access to 'Pages' section" and "Access to 'Files' section"
    And a "page" "My page"
    And a "folder" "assets/Uploads"
    And a "file" "assets/Uploads/test1.pdf"

  Scenario: Operate elemental fileblocks
    Given I am logged in as a member of "EDITOR" group
    When I go to "/admin/pages"
    And I follow "My page"

    # Add file block
    And I press the "Add block" button
    And I wait for 1 seconds
    And I click on the ".font-icon-block-file" element
    And I wait for 5 seconds
    And I press the "Save" button

    # <<< issue here, complaining that there's something wrong with the fileblock
    # presumably not setting up folders/files correctly?

    # Open inline editing and select file from gallery
    And I click on the ".element-editor-header__expand" element
    And I wait for 5 seconds
    And I press the "Choose existing" button
    And I wait for 1 seconds
    And I click on the ".gallery-item__thumbnail" element
    And I press the "Insert" button
    And I press the "Publish" button

    # Assert that it saved
    And I click on the ".element-editor-header__title" element
    Then I should see a ".uploadfield-item--document" element
