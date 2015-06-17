package com.dvsa.mot.selenium.priv.frontend.vehicletest.braketest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfigurationPageField;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResultsPageField;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MOTRetestPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class BrakeTestSummaryPage extends BasePage {
    private final String PAGE_TITLE = "BRAKE TEST RESULTS";

    @FindBy(id = "brake_test_summary_done") private WebElement doneButton;

    @FindBy(id = "addBrakeTestResults") private WebElement editButton;


    public BrakeTestSummaryPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(PAGE_TITLE);
    }

    public static BrakeTestSummaryPage navigateHereFromLoginPage(WebDriver driver, Login login,
            Vehicle vehicle, Map<BrakeTestConfigurationPageField, Object> configEntries,
            Map<BrakeTestResultsPageField, Object> resultEntries) {
        BrakeTestResultsPage
                .navigateHereFromLoginPageAsMotTest(driver, login, vehicle, configEntries)
                .enterBrakeResultsPageFields(resultEntries).submit();
        return new BrakeTestSummaryPage(driver);
    }

    public MotTestPage clickDoneButton() {
        doneButton.click();
        return new MotTestPage(driver);
    }

    public MotTestPage clickDoneButton(String title) {
        doneButton.click();
        return new MotTestPage(driver, title);
    }

    public MOTRetestPage clickDoneExpectingMotRetestPage() {
        doneButton.click();
        return new MOTRetestPage(driver);
    }

    public BrakeTestConfigurationPage clickEditButton() {
        editButton.click();
        return new BrakeTestConfigurationPage(driver);
    }

    private Map<String, String> getMapFromTable(String tableId, String finalResultId) {
        Map<String, String> resultsMap = new HashMap<String, String>();
        String pageText = driver.getPageSource();

        if (pageText.contains("\"" + tableId + "\"")) {
            WebElement table = driver.findElement(By.id(tableId));

            List<WebElement> rows =
                    table.findElement(By.tagName("tbody")).findElements(By.tagName("tr"));
            for (WebElement r : rows) {
                String description = r.findElements(By.tagName("td")).get(0).getText();
                String result = r.findElements(By.tagName("td")).get(1).getText().replace("%", "");
                resultsMap.put(tableId + "_" + description.trim(), result);
            }
        }

        if (pageText.contains(finalResultId)) {
            resultsMap.put(finalResultId,
                    driver.findElement(By.id(finalResultId)).findElement(By.tagName("strong"))
                            .getText());
        }
        return resultsMap;
    }

    public Map<String, String> getResultsMap() {
        Map<String, String> map = new HashMap<String, String>();
        map.putAll(getMapFromTable("control-1-results", "control-1-result"));
        map.putAll(getMapFromTable("control-2-results", "control-2-result"));
        map.putAll(getMapFromTable("service-brake-results", "service-brake-result"));
        map.putAll(getMapFromTable("service-brake2-results", "service-brake2-result"));
        map.putAll(getMapFromTable("brake-imbalance-results", "brake-imbalance-result"));
        map.putAll(getMapFromTable("parking-brake-results", "parking-brake-result"));
        System.out.println(map);
        return map;
        
        /*String pageText = driver.getPageSource();
        
        BrakeTestSummaryPageField[] b = BrakeTestSummaryPageField.values();
        for (BrakeTestSummaryPageField brakeTestSummaryPageField : b) {
            System.out.println("Exist? "+pageText.contains(brakeTestSummaryPageField.getId()));
        }*/
    }

}
