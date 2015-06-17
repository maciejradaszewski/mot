Name:     mot
Version:  %buildversion
Release:  %dist
Vendor:   DVSA
Summary:  MOT
License:  N/A

Source0:  mot-common-web-module*.tar
Source1:  mot-api*.tar
Source2:  mot-web-frontend*.tar

BuildRoot: %{_tmppath}/%{name}-%{version}-%{release}-root-%(%{__id_u} -n)

%define php php55
%define prefix /opt/dvsa
%define confprefix %{_sysconfdir}/dvsa

BuildArch: noarch

%description
MOT


%package common-web-module
Summary: MOT Common Web Module
Requires: %php
%description  common-web-module
Required components common to other MOT components


%package api
Summary: MOT API
Requires: %php mot-common-web-module
%description api
MOT API


%package web-frontend
Summary: MOT Web Frontend
Requires: %php mot-common-web-module
%description web-frontend
MOT Web Frontend


%prep
%setup -cn MOT
%setup -cn MOT -T -D -b 1
%setup -cn MOT -T -D -b 2


%build
/bin/true


%install
echo `pwd` > /tmp/startingpoint.txt
%{__mkdir_p} %{buildroot}%{prefix}
%{__mkdir_p} %{buildroot}%{confprefix}
echo `pwd` > /tmp/pre-commmon.txt
#%{__mkdir_p} %{buildroot}%{prefix}
%{__cp} -a  mot-common-web-module/ %{buildroot}%{prefix}
echo `pwd` > /tmp/pre-mot.txt
#%{__mkdir_p} %{buildroot}%{prefix}
%{__cp} -a mot-api/ %{buildroot}%{prefix}
#%{__mkdir} %{buildroot}%{confprefix}/mot-api

#%{__mkdir_p} %{buildroot}%{prefix}
#%{__cp} -a . %{buildroot}%{prefix}
%{__cp} -a mot-web-frontend %{buildroot}%{prefix}
%{__mkdir} %{buildroot}%{confprefix}/mot-web-frontend


%clean
%{__rm} -rf %{buildroot}


%files common-web-module
%defattr(0644,root,root,0755)
%{prefix}/mot-common-web-module


%files api
%defattr(0644,root,root,0755)
%{prefix}/mot-api
%config(noreplace) %{confprefix}


%files web-frontend
%defattr(0644,root,root,0755)
%{prefix}/mot-web-frontend
%config(noreplace) %{confprefix}


