package uk.gov.dvsa;

import uk.gov.dvsa.domain.model.User;

import java.util.Random;

public class RiskScoreAssessmentParameterSetter {

    private User user;
    private String vts;
    private Boolean someoneElseEnterSiteAssessment;
    private Boolean cancelAddSiteAssessment;
    private Boolean update;
    private Boolean clickSubmit;
    private String scorePoints;
    private Random rand = new Random();

    public RiskScoreAssessmentParameterSetter setUser(User user) {
        this.user = user;
        return this;
    }

    public User getUser() {
        return user;
    }

    public RiskScoreAssessmentParameterSetter setVts(String vts) {
        this.vts = vts;
        return this;
    }

    public String getVts() {
        return vts;
    }

    public RiskScoreAssessmentParameterSetter setSomeoneElseEnterSiteAssessment(Boolean someoneElseEnterSiteAssessment) {
        this.someoneElseEnterSiteAssessment = someoneElseEnterSiteAssessment;
        return this;
    }

    public Boolean getSomeoneElseEnterSiteAssessment() {
        return someoneElseEnterSiteAssessment;
    }

    public RiskScoreAssessmentParameterSetter setCancelAddSiteAssessment(Boolean cancelAddSiteAssessment) {
        this.cancelAddSiteAssessment = cancelAddSiteAssessment;
        return this;
    }

    public Boolean getCancelAddSiteAssessment() {
        return cancelAddSiteAssessment;
    }

    public RiskScoreAssessmentParameterSetter setUpdateExistingScoreAssessment(Boolean update) {
        this.update = update;
        return this;
    }

    public Boolean getUpdateExistingScoreAssessment() {
        return update;
    }

    public RiskScoreAssessmentParameterSetter setClickSubmitToUpdateSiteAssessmentOnSummaryPage(Boolean clickSubmit) {
        this.clickSubmit = clickSubmit;
        return this;
    }

    public Boolean getClickSubmitToUpdateSiteAssessmentOnSummaryPage() {
        return clickSubmit;
    }

    public String getScorePoints() {
        return scorePoints = String.format("%s.%s", rand.nextInt(999) + 1, rand.nextInt(99) + 1);
    }
}
