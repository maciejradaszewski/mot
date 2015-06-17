#!/usr/bin/perl
use Digest::MD5 qw(md5_base64);
use Data::Dumper;
use POSIX qw(strftime);

sub anon_line (@) {
	my ($filename, $pattern,$lines) = @_;
   warn "Generating $filename using $pattern with $lines lines of data\n";
	#$lines=10;
	my $code ="srand(${lines});\n";
	my $count=0;
	$code .="my \$p=";
	foreach my $col (split //,$pattern) {
		$code .=".'|'." if ($count);
      if ($col eq 'N') {    	
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
	$code .=";\n";
	$code .="print OUTPUT \"\$p\\n\";\n";
	my $sub="open(OUTPUT, '|gzip - > $filename.csv.gz');\n";
   $sub .="foreach my \$line (1..$lines) {\n$code}\n";
   $sub .="close OUTPUT;\n\n";
	return $sub;
}

while(<>) {
	chomp;
	#print anon_line(split (/\|/, $_));
	my $pid = fork();
	if ($pid) {
      eval (anon_line(split /\|/, $_));
      die ("Finished writing\n");
   }
}
