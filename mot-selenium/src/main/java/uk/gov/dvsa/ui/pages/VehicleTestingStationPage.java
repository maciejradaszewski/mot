package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;

public class VehicleTestingStationPage extends Page {
    public static final String path = "/vehicle-testing-station/%s";
    private static final String PAGE_TITLE = "Vehicle Testing Station";

    @FindBy(id = "assign-a-role") private WebElement assignARoleLink;
    @FindBy(css = "div.text") private WebElement getRole;
    @FindBy(id = "validation-message--success") private WebElement validationMessageSuccess;
    @FindBy(id = "email") private WebElement emailValue;
    @FindBy(id = "phone-number") private WebElement phoneNumberValue;
    @FindBy(id = "remove-role") private WebElement removeTesterRole;
    @FindBy(id = "event-history") private WebElement viewEventHistoryLink;
    @FindBy(id = "site-address") private WebElement vtsAddress;
    @FindBy(id = "email") private WebElement vtsEmail;
    @FindBy(id = "phone-number") private WebElement vtsPhoneNumber;

    public VehicleTestingStationPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public VehicleTestingStationPage assignARole() {
        assignARoleLink.click();

        return this;
    }

    public boolean isValidationMessageSuccessDisplayed() {
        return (validationMessageSuccess.isDisplayed());
    }

    public String getTesterName(String id) {
        By nameLocator = By.cssSelector(String.format(".key-value-list a[href*='%s']", id));

        if(driver.findElements(nameLocator).isEmpty()) {
            return "Tester not found on this page";
        }

        return driver.findElement(nameLocator).getText();
    }

    public String getRole() {
        return getRole.getText();
    }

    public String getEmailValue() {
        return emailValue.getText();
    }

    public String getPhoneNumberValue() {
        return phoneNumberValue.getText();
    }

    public RemoveRolePage removeTesterRole(String id){
        String testerRemoveLink = String.format("#role-assignment-%s-TESTER td a", id);
        WebElement removeTesterRole = driver.findElementByCssSelector(testerRemoveLink);
        removeTesterRole.click();

        return new RemoveRolePage(driver);
    }

    public void clickOnViewHistoryLink() {
        viewEventHistoryLink.click();
    }

    public boolean isTesterDisplayed(String id, String name){
        return getTesterName(id).equals(name);
    }

    public boolean isVtsAddressDisplayed(){
        return vtsAddress.isDisplayed();
    }

    public boolean isVtsEmailDisplayed(){
        return vtsEmail.isDisplayed();
    }

    public boolean isVtsPhoneNumberDisplayed(){
        return vtsPhoneNumber.isDisplayed();
    }

    public boolean isActiveMotTestDisplayed(String vehicleRegistrationNumber) {
        return driver.findElement(By.linkText(vehicleRegistrationNumber)).getText().contains(vehicleRegistrationNumber);
    }

    public TestShortSummaryPage clickOnActiveTest(String regNum) {
        driver.findElement(By.linkText(regNum)).click();

        return new TestShortSummaryPage(driver);
    }
}
