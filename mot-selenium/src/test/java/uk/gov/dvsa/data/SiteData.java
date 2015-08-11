package uk.gov.dvsa.data;

import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.service.SiteService;

import java.io.IOException;

public class SiteData extends SiteService{

    private AeData aeData = new AeData();

    public SiteData() {}

    public Site createNewSite(int aeId, String name) throws IOException {
        return createSite(aeId, name);
    }

    public Site createSite() throws IOException {
        return createSite(aeData.createAeWithDefaultValues().getId(), "default");
    }
}
