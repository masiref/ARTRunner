<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Definition\Call\Then;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Exception\ExpectationException;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext {

    private $output;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct() {

    }

    private function getPage() {
        return $this->getSession()->getPage();
    }

    private function getObject($locator, $namedSelector) {
        $page = $this->getPage();
        $object = $page->find('css', $locator);
        if (null === $object) {
            $selector = array($namedSelector, $locator);
            $object = $page->find('named', $selector);
        }
        return $object;
    }

    private function iShouldSeeObject($locator, $namedSelector) {
        $object = $this->getObject($locator, $namedSelector);
        $visible = null !== $object && $object->isVisible();
        if (!$visible) {
            throw new ElementNotFoundException($this->getSession(), 'element', 'named', $namedSelector . ' ' . $locator);
        }
        return $object;
    }

    private function iShouldNotSeeObject($locator, $namedSelector) {
        $message = sprintf("%s %s appears on this page, but it should not.", ucfirst($namedSelector), $locator);
        $object = $this->getObject($locator, $namedSelector);
        $visible = null !== $object && $object->isVisible();
        if ($visible) {
            throw new ExpectationException($message, $this->getSession());
        }
        return;
    }

    /**
     * @Then I should see :title modal
     */
    public function iShouldSeeModal($title) {
        sleep(1);
        $this->assertPageContainsText($title);
    }

    /**
     * @Then I should see :locator checkbox
     */
    public function iShouldSeeCheckbox($locator) {
        return $this->iShouldSeeObject($locator, 'checkbox');
    }

    /**
     * @Then I should see :locator link
     */
    public function iShouldSeeLink($locator) {
        return $this->iShouldSeeObject($locator, 'link');
    }

    /**
     * @Then I should see :locator list
     */
    public function iShouldSeeList($locator) {
        return $this->iShouldSeeObject($locator, 'select');
    }

    /**
     * @Then :selectLocator should be selected with :option
     */
    public function shouldBeSelectedWith($selectLocator, $option) {
        $select = $this->iShouldSeeObject($selectLocator, 'select');
        $selectedOption = trim($select->getValue());
        if ($selectedOption === $option) {
            return;
        }
        $message = sprintf('Selected option is "%s", but "%s" expected.', $selectedOption, $option);
        throw new ExpectationException($message, $this->getSession());
    }

    /**
     * @Then I should see :locator field
     */
    public function iShouldSeeField($locator) {
        return $this->iShouldSeeObject($locator, 'field');
    }

    /**
     * @Then I should not see :locator button
     */
    public function iShouldNotSeeButton($locator) {
        return $this->iShouldNotSeeObject($locator, 'button');
    }

    /**
     * @Then I should not see :locator checkbox
     */
    public function iShouldNotSeeCheckbox($locator) {
        return $this->iShouldNotSeeObject($locator, 'checkbox');
    }

    /**
     * @Then I should not see :locator link
     */
    public function iShouldNotSeeLink($locator) {
        return $this->iShouldNotSeeObject($locator, 'link');
    }

    /**
     * @Then I should not see :locator list
     */
    public function iShouldNotSeeList($locator) {
        return $this->iShouldNotSeeObject($locator, 'select');
    }

    /**
     * @Then I should not see :locator field
     */
    public function iShouldNotSeeField($locator) {
        return $this->iShouldNotSeeObject($locator, 'field');
    }

}
