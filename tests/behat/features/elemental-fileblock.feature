Feature: Use elemental fileblock
  As a website user
  I want to use elemental fileblock

  Background:
    Given I add an extension "DNADesign\Elemental\Extensions\ElementalPageExtension" to the "Page" class
    And the "group" "EDITOR group" has permissions "CMS_ACCESS_LeftAndMain"
    And a "page" "My page"
    And a "folder" "assets/Uploads"
    And a "file" "assets/Uploads/test1.pdf"
    And I am logged in with "EDITOR" permissions
    And I go to "/admin/pages"
    And I click on "My page" in the tree

  Scenario: Operate elemental fileblocks
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

  Scenario: I can use modal breadcrumbs to navigate up levels
    # Add file block
    When I press the "Add block" button
      And I wait for 1 seconds
      And I click on the ".font-icon-block-file" element
      And I wait for 5 seconds
      And I press the "Save" button

      # Open inline editing and select file from gallery
      And I click on the ".element-editor-header__expand" element
      And I wait for 5 seconds
      And I press the "Choose existing" button
      And I wait for 1 seconds

      # Test breadcrumbs
      And I press the "Back" HTML field button
      And I select the file named "folder1" in the gallery
      And I select the file named "folder1-1" in the gallery
    Then I should see the breadcrumb link "Files"
      And I should see the breadcrumb link "folder1"
      And I should not see the breadcrumb link "folder1-1"
    When I click on the breadcrumb link "folder1"
      Then I should see the file named "folder1-1" in the gallery
      And I should not see the breadcrumb link "folder1"
      # Validate that we haven't navigated away from the pages admin
      And I can see the preview panel
    When I click on the breadcrumb link "Files"
    Then I should see the file named "folder1" in the gallery
      And I should not see the breadcrumb link "Files"
      # Validate that we haven't navigated away from the pages admin
      And I can see the preview panel
