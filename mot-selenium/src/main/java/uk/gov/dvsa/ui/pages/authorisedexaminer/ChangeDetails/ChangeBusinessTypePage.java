package uk.gov.dvsa.ui.pages.authorisedexaminer.ChangeDetails;

import com.dvsa.mot.selenium.datasource.enums.CompanyType;
import org.openqa.selenium.By;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;

public class ChangeBusinessTypePage extends ChangeAEDetailsPage {
    public static final String PATH = "/authorised-examiner/%s/business-type/change";
    public static final String PAGE_TITLE = "Change business type";

    public ChangeBusinessTypePage(MotAppDriver driver) {
        super(driver, PAGE_TITLE);
    }

    public ChangeBusinessTypePage chooseBusinessType(CompanyType businessType) {
        FormCompletionHelper.selectInputBox(driver.findElement(By.cssSelector(String.format("input[value='%s']", businessType.getValue()))));
        return this;
    }
}
