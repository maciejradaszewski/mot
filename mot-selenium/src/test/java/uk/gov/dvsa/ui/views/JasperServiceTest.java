package uk.gov.dvsa.ui.views;

import org.testng.annotations.Test;
import uk.gov.dvsa.domain.service.FeaturesService;
import uk.gov.dvsa.ui.BaseTest;

import java.io.IOException;

public class JasperServiceTest extends BaseTest
{
    @Test(groups = {"BVT", "Regression"})
    public void showAsyncHeaderOnHomePage() throws IOException {
        //Given Jasper Async is set to false
        FeaturesService service = new FeaturesService();
        //When I view my HomePage as a tester
        System.out.println(service.getToggleValue("jasper.async"));

        //Then I should not see the "Your VTS activity" Header

        //And the MOT test certificates Link
    }

    @Test(groups = {"BVT", "Regression"})
    public void ShowAsyncPageAndCertificateListTest()
    {
        //Given Jasper Async is set to false

        //When I view my HomePage as a tester

        //Then I should not see the "Your VTS activity" Header

        //And the MOT test certificates Link
    }

    @Test(groups = {"BVT", "Regression"})
    public void ShowAsyncPageAndCertificateListTest()
    {
        //Given Jasper Async is set to false

        //When I view my HomePage as a tester

        //Then I should not see the "Your VTS activity" Header

        //And the MOT test certificates Link
    }


}
