#!/usr/bin/perl
use Digest::MD5 qw(md5_base64);
use Data::Dumper;

$pattern=$ARGV[0]||'NNAAAAAASSR';
warn "Using $pattern\n";

my $cachenum={};
my $cachestr={};
sub f_scan ($) {	
   my $temp_array=[];
   

	return $cachenum->{$_[0]};
}

sub f_anon_str ($) {
	if (!$cachestr->{$_[0]}) {	
		my $md5= md5_base64($_[0]);
		$cachestr->{$_[0]} = $md5 if (length($_[0])<32);
		return $md5;
	} else {
	   return $cachestr->{$_[0]};
	}
}

sub anon_line () {
	my ($filename, $pattern,$lines) = @_;
   warn "Processing $filename using $pattern with $lines lines of data\n";
	my $regf ='^';
	my $regr ="my \@p=[];\n";
	my $count=0;
	my $last='';
	my $buff='';
	my $bufr='';
	foreach my $col (split //,$pattern) {
      if (($last ne $col) && ($last eq 'A')) {
  		   #$regf.='\|' if ($count);
			$regf.=$buff.'\|';      	
			$regr.=$bufr;
			$buff='';
			$bufr='';
			#$count++;
		}  elsif (($last ne 'A') && $count) {
  		   $regf.='\|';
		}
      $count++;
	$code .="my \$p=";
	foreach my $col (split //,$pattern) {
		$code .=".'|'." if ($count);
      if ($col eq 'R') {
			$regf.='(.*)$';
			$code.="\$${count};\n";      
      } elsif ($col eq 'N') {
			$code .="int(rand(2**31))";      
      } elsif ($col eq 'n') {     	
			$code .="int(rand(2**15))";      
      } elsif ($col eq 'S') {     	
			$code .="md5_base64(rand())";      
      } elsif ($col eq 'D') {     	
         $code .= "strftime (\"%Y-%m-%d\", gmtime(rand(2**31)))";
      } elsif ($col eq 'T') {     	
         $code .= "strftime (\"%Y-%m-%d %H:%M:%S\", gmtime(rand(2**31)))";
      } else {
			die ("Ooops not supposed to use '$col'!");   
      }
		$count++;;
      #$regr.="print \"${count} -> \$${count} \\n\";\n";
	}
			$regf.='(.*?)';      	
			$regr.="srand(\$${count});\$p[${count}-1]=int(rand(2**32));\n";      
      } elsif ($col eq 'S') {
			$regf.='(.*?)';      	
			$regr.="\$p[${count}-1]=md5_base64(\$${count});\n";      
      } elsif ($last eq $col) {
      	$buff =~ s/\)$/\\|/ ;
			$buff = '(' if ($buff eq '');
			$buff.='.*?)';      	
         $count--;
      } else {
			$buff='(.*?)';      	
			$bufr="\$p[${count}-1]=\$${count};\n";      
      }
		$last=$col;
      #$regr.="print \"${count} -> \$${count} \\n\";\n";
	}
	$regr.="print (join '|',\@p); print \"\\n\";\n";
	$code .="print OUTPUT \"\$p\\n\";\n";
	my $sub="my count=0;\n";
   $sub .="open(OUTPUT, '|gzip - > $filename.csv.gz');\n";
   $sub .="open INPUT, '<$filename.csv';\n";
   $sub .="while (<INPUT>) {\n$code}\n";
   $sub .="close INPUT;\n";
   $sub .="close OUTPUT;\n";
   $sub .="if ($count<$lines) {";
   $sub .=" warn \"Not enough data in $filename.csv expected $lines got $count\\n\";\n";
   $sub .="} elsif ($count>$lines) {\b";
   $sub .=" warn \"Too much data in $filename.csv expected $lines got $count\\n\";\n";
   $sub .="}";
   $sub .="\n";
   return $sub;
}

while(<>) {
	chomp;
	push @records, [split /\|/, $_];
}

foreach my $record (@records) {
	if 
}	


die "scan completed";

foreach my $record (@records) {
	my $pid = fork();
	if ($pid) {
      eval (anon_line(@$record));
      die ("Finished writing\n");
   }
}
